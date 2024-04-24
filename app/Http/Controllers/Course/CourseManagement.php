<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\{Course, User, Instructor_Permissions,
    Provider, Co_Provider,
    Instructor_Resume, Handout, 
    Instructor, Profession, Section, Video, Article, 
    Course_Progress,
    Quiz, Quiz_Item, Quiz_Score, Instructional_Design, Notification};
use App\Http\Requests\StoreImage;
use Illuminate\Support\Facades\{Storage,Auth,DB};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\{LengthAwarePaginator};

use App\Jobs\{UploadBigFilesToAwsS3};

use \Aws\S3\{S3Client, MultipartUploader, ObjectUploader};
use Aws\MediaConvert\MediaConvertClient;  
use Aws\Exception\{AwsException,MultipartUploadException};

use AWS;
use Response;
use stdClass;
use Session;
use URL;

class CourseManagement extends Controller
{
    function details(Request $request){
        $provider_professions = array_map(function($id){
            return Profession::find($id);
        }, json_decode(_current_provider()->profession_id));

        $course_professions = array_map(function($id){
            return Profession::find($id);
        }, json_decode(_current_course()->profession_id));

        $data = [
            'provider_professions' => $provider_professions,
            'course_professions' => $course_professions,
            'course_profession_ids' => json_decode(_current_course()->profession_id),
        ];

        if(_my_course_permission("course_details") && _course_creation_restricted("course_details")){
            return view('page/courseManagement/index', $data);  
        }

        return view('template/errors/404');  
    }

    function handout(Request $request){
        $provider_professions = array_map(function($id){
            return Profession::find($id);
        }, json_decode(_current_provider()->profession_id));

        $course_professions = array_map(function($id){
            return Profession::find($id);
        }, json_decode(_current_course()->profession_id));

        $data = [
            'provider_professions' => $provider_professions,
            'course_professions' => $course_professions,
            'course_profession_ids' => json_decode(_current_course()->profession_id),
        ];
        
        if(_my_course_permission("handouts") && _course_creation_restricted("handouts")){
            return view('page/course_creation/create_content/handout', $data);
        }
        
        return view('template/errors/404');  
    }

    function submit_accreditation(Request $request){
        $instructor = User::whereIn("id", _current_course()->instructor_id ? json_decode(_current_course()->instructor_id) : [])->get();
        $instructional_design = Instructional_Design::where([
            "type" => "course",
            "data_id" => _current_course()->id,
            "deleted_at" => null
        ])->get();
        $data = [
            "accreditor_account" => $this->checkAccreditor(),
            "learning_outcomes" => $this->get_sections(_current_course()->id),
            "objectives" => json_decode(_current_course()->objectives),
            "course_instructors" => $instructor,
            "course_instructor_ids" => [],
            "instructional_design" => $instructional_design

        ];
        
        // if(_my_course_permission("accreditation") && _course_creation_restricted('accreditation')){
        //     return view('page/courseManagement/submitAccreditation', $data);
        // }

        if(_my_course_permission("accreditation")){
            return view('page/courseManagement/submitAccreditation', $data);
        }
        
        return view('template/errors/404');  
    }

    function can_submit_accreditation(Request $request) : JsonResponse 
    {
        $errors = [];
        if(!_course_creation_check('course_details') ){
           $errors[] = "Course Details" ;
        }

        if(!_course_creation_check('attract_enrollments')){
           $errors[] = "Attract Enrollments" ;
        }

        if(!_course_creation_check('instructors')){
            $errors[] = "Instructors" ;
        }

        if(!_course_creation_check('video_content')){
            $errors[] = "Video & Content" ;
        }

        if(!_course_creation_check('grading')){
            $errors[] = "Grading Assessments" ;
        }

        if(!_course_creation_check('handouts')){
            $errors[] = "Handouts" ;
        }

        $course = Course::find(_current_course()->id);
        if($course && $course->instructor_id!=null){
            $instructors = json_decode($course->instructor_id);
            foreach($instructors as $id){
                $user = User::select("name")->where(["id"=>$id])->first();
                $resume = Instructor_Resume::where([
                    "user_id" => $id,
                    "deleted_at" => null,
                ])->first();

                if($resume){}else{
                    $errors[] = "Instructor {$user->name}: PRC Resume incomplete";
                }
            }
        }
        return response()->json(["allow"=>count($errors) > 0 ? false :  true, "errors"=>$errors]);
    }

    function content_sections(Request $request) : JsonResponse
    {
        $sections = Section::where([
            "type"=>"course", "data_id"=> _current_course()->id,
            "deleted_at" => null,
        ])->get();
        foreach ($sections as $key => $section) {
            $detailed_sequence = [];

            if($section->sequences){
                $sequence = json_decode($section->sequences);

                foreach ($sequence as $key => $seq) {
                    
                    switch ($seq->type) {
                        case 'video':
                            $video = Video::where([
                                "section_id"=>$section->id, 
                                "id"=>$seq->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
                            if($video){ $detailed_sequence[] = array_merge((array)$seq, $video->toArray()); }
                            break;
    
                        case 'article':
    
                            $article = Article::where([
                                "section_id"=>$section->id, 
                                "id"=>$seq->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
                            if($article){ $detailed_sequence[] = array_merge((array)$seq, $article->toArray()); }
                            break;
    
                        case 'quiz':
    
                            $quiz = Quiz::where([
                                "section_id"=>$section->id, 
                                "id"=>$seq->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
                            if($quiz){ 
                                $quiz->items = Quiz_Item::where([
                                    "quiz_id" => $quiz->id,
                                    "deleted_at" => null,
                                ])->get();

                                $detailed_sequence[] = array_merge((array)$seq, $quiz->toArray()); 
                            }
                            break;
                    }
                }
            }

            $section->detailed_sequence = $detailed_sequence;
        }

        return response()->json(["data"=>$sections], 200);
    }

    function preview_video(Request $request) 
    {
        $video = Video::find($request->id);
        $data = [
            "video" => $video,
        ];

        return view('page/courseManagement/video/preview', $data);
    }

    function uploadVideo(Request $request) : JsonResponse
    {
        $file = $request->file('files');
        $file_extension = $file->getClientOriginalExtension();
        if(in_array($file_extension, ['jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'])){
            $name = "course_poster_"._current_course()->id.uniqid().".{$file_extension}";
        }else{
            $name = "course_video_"._current_course()->id.uniqid().".{$file_extension}";
        }
        
        $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$name}";
        $path = Storage::disk('s3')->put($filepath,file_get_contents($file));
        if($path){
            $pathname = Storage::disk('s3')->url($filepath);

            $update = Course::find(_current_course()->id);
            if(in_array($file_extension, ['jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'])){
                $update->course_poster = $pathname;
            }else{
                $update->course_video = $pathname;
            }

            $update->save();
            return response()->json(["pathname" => $pathname, "extension" => strtolower($file_extension), "filename"=> $name]);
        }
        return response()->json($path);
    }

    /**
     * OLD 
     */
    function uploadSectionVideo(Request $request) : JsonResponse
    {
        $file = $request->file('files');
        $file_extension = $file->getClientOriginalExtension();
        $name = "course_content-video_"._current_course()->id.uniqid().".{$file_extension}";
        $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$name}";
        $path = Storage::disk('s3')->put($filepath,file_get_contents($file));
        if($path){
            $pathname = Storage::disk('s3')->url($filepath);
            return response()->json(["pathname" => $pathname, "extension" => strtolower($file_extension), "filename"=> $name]);
        }
        return response()->json($path);
    }

    function section_video_upload(Request $request) : JsonResponse
    {
        $video_id = $request->video_id;
        $video = Video::find($video_id);
        
        $duration = 0;
        $file = $request->file('files');
        $file_extension = $file->getClientOriginalExtension();
        $name = "course_content-video_"._current_course()->id.uniqid().".{$file_extension}";
        $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$name}";
        $filesize = $file->getSize();

        $s3disk = Storage::disk('s3');
        if($file->move(storage_path("temporary/course/video"), $name)){
            $video->filename = $name;
            $video->size = $filesize;
            $video->uploading_status = "uploading";
            $video->save();

            $this->dispatch(new UploadBigFilesToAwsS3([
                "filename" => $name,
                "filepath" => $filepath,
            ], $video_id));
        }

        $filedata = ["upload_status"=>"uploading", "filename"=> $name, "video_data" => $video];
        return response()->json($filedata);

        return response()->json([], 422);
    }

    function section_video_cancel_upload(Request $request) : JsonResponse
    {   
        $status = 422;
        $video_id = $request->video_id;
        $section = $request->section;
        $part = $request->part;

        $video = Video::find($video_id);
        if($video){
            if($video->cdn_url){
                $s3disk = Storage::disk('s3');
                $s3Bucket = $s3disk->getDriver()->getAdapter()->getBucket();
                $s3Client = $s3disk->getDriver()->getAdapter()->getClient();

                $explodedURL = explode(".com/", $video->cdn_url);
                $keypath = $explodedURL[1];

                unlink(storage_path("temporary/course/video/{$video->filename}"));
                $s3Client->deleteObject([
                    'Bucket' => $s3Bucket,
                    'Key'    => $keypath
                ]);
            }

            $video->cdn_url = null;
            $video->filename = null;
            $video->size = null;
            $video->length = 0;
            $video->uploading_status = null;
            $video->save();

            $status = 200;
        }

        return response()->json([], $status);
    }

    function section_video_remove(Request $request) : JsonResponse
    {
        $status = 422;
        $data = [];

        $video_id = $request->video_id;
        $section = $request->section;
        $part = $request->part;

        $video = Video::find($video_id);
        if($video){
            $data = [
                "title" => $video->title,
            ];

            if($video->cdn_url){
                $s3disk = Storage::disk('s3');
                $s3Bucket = $s3disk->getDriver()->getAdapter()->getBucket();
                $s3Client = $s3disk->getDriver()->getAdapter()->getClient();

                $explodedURL = explode(".com/", $video->cdn_url);
                $keypath = $explodedURL[1];

                $s3Client->deleteObject([
                    'Bucket' => $s3Bucket,
                    'Key'    => $keypath
                ]);
            }

            $video->cdn_url = null;
            $video->filename = null;
            $video->size = null;
            $video->length = 0;
            $video->uploading_status = null;
            $video->save();

            
            $status = 200;
        }

        return response()->json($data, $status);   
    }

    function section_video_refresh(Request $request) : JsonResponse
    {
        $status = 422;
        $data = [];

        $video_id = $request->video_id;
        $section = $request->section;
        $part = $request->part;

        $video = Video::find($video_id);
        if($video){
            $data = [
                "title" => $video->title,
            ];

            if($video->cdn_url){
                $status = 200;
                $cdn_url = $video->cdn_url;
            }else{
                $keypath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$video->filename}";
                $s3disk = Storage::disk('s3');
                $s3Bucket = $s3disk->getDriver()->getAdapter()->getBucket();
                $exists = $s3disk->exists($keypath);

                if($exists){
                    $status = 200;

                    $cdn_url = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/{$keypath}";
                    $video->cdn_url = $cdn_url;
                }
            }

            if($status==200){
                $video->uploading_status = "done";

                $duration = 0;
                $filesize = 0;

                /**
                 * Getting Video Length
                 * 
                 */
                if ($fp_remote = fopen($cdn_url, 'rb')) {
                    $localtempfilename = tempnam('/tmp', 'getID3');
                    if ($fp_local = fopen($localtempfilename, 'wb')) {
                        while ($buffer = fread($fp_remote, 8192)) {
                            fwrite($fp_local, $buffer);
                        }
                        fclose($fp_local);
                        $getID3 = new \getID3;
                        $ThisFileInfo = $getID3->analyze($localtempfilename);
                        $duration = $ThisFileInfo['playtime_string'];
                        $filesize = $ThisFileInfo['filesize'];
                        unlink($localtempfilename);
                    }
                }

                $video->size = $filesize;
                $video->length = $duration;
                $video->save();

                $data["duration"] = $duration;
                $data["video_id"] = $video->id;
            }
        }

        return response()->json($data, $status);   
    }

    function uploadFile(Request $request) : JsonResponse
    {
        $file = $request->file('files');
        $file_extension = $file->getClientOriginalExtension();
        $name = "course_handout_"._current_course()->id.uniqid().".{$file_extension}";
        $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/handouts/{$name}";
        $path = Storage::disk('s3')->put($filepath,file_get_contents($file));
        if($path){
            $pathname = Storage::disk('s3')->url($filepath);
            return response()->json(["pathname" => $pathname, "extension" => strtolower($file_extension), "filename"=> $name]);
        }
        return response()->json($path);
    }

    function StoreCourseDetails(Request $request) : JsonResponse {
        $id = _current_course()->id;
        $user = User::find(Auth::user()->id);
        $provider_id = $user ? ($user->provider_id ? $user->provider_id : 0) : 0;
        $course_title = $request->course_title;
        $headline = $request->headline;
        $description = $request->about;
        $profession_cater = isset($request->profession_cater) ? json_encode($request->profession_cater) : null;
        $start_date = date("Y-m-d H:i:s",strtotime($request->start_date));
        $end_date =  date("Y-m-d H:i:s",strtotime($request->end_date));
        $language = $request->language;


        $sample = [
            "id" => 1,
            "name" => "Sample",
            "date" => date("Y-m-d H:i:s")
        ];
        if($id){
            $course = Course::where("id",$id)->update([
                "title" => $course_title,
                "headline" => $headline,
                "description" => $description,
                "session_start" => $start_date,
                "session_end" => $end_date,
                "language" => $language,
            ]);
        }else{
            $course = new Course;

            $course->provider_id = json_encode($provider_id);
            $course->title = $course_title;
            $course->profession_id = $profession_cater;
            $course->headline = $headline;
            $course->description = $description;
            $course->session_start = $start_date;
            $course->session_end = $end_date;
            $course->language = $language;
            //Fields that i'm not sure
            $course->url = "";
            $course->instructor_id = null;
            $course->price = 0;
            $course->program_accreditation_no = "";
            $course->certificate_templates = null;
            $course->course_poster ="";
            $course->course_video ="";
            $course->marketing_description ="";
            $course->objectives = null;
            $course->requirements = null;
        
            $course->target_students = null;
            $course->assessment = null;
            $course->pass_percentage = 0;
            $course->total_unit_amounts = "";
            $course->allow_retry = "";
            $course->allow_forward = "";
            $course->prc_status = "draft";
            $course->status = "draft";
            $course->approved_at =null;
            $course->save();
        }
        

        if($course){
            if(! Session::has('course_id')){
                Session::put("course_id",$course->id);
            }
            return response()->json(["status" => 200 , "message" => "Course Details Saved!"]);
        }else{
            return response()->json(["status" => 422, "message" => "Unable to create course!"]);
        }
    }

    function StoreAttractEnrollments(Request $request) : JsonResponse{
    
        if(session::has("course_id")){
            $update = Course::find(_current_course()->id);
            $type = $request->store;

            switch ($type) {
                case 'objectives':
                    $update->objectives = json_encode($request->objectives);
                    break;

                case 'requirements':
                    $update->requirements = json_encode($request->requirements);
                    break;

                case 'target_students':
                    $update->target_students = json_encode($request->target_students);
                    break;
            }

            if($update->save()){
                return response()->json(["status" => 200, "message" => "Successfully saved!"]);
            }

            return response()->json(["status" => 500, "message" => "Unable to save updates! Please refresh your browser"]);
       }else{
        return response()->json(["status" => 500, "message" => "Course not found!"]);
       }
    }

    function StoreInstructional(Request $request) : JsonResponse{
        $id = _current_course()->id;
        $find = Instructional_Design::where([
            "type" => "course",
            "data_id" => _current_course()->id,
            "deleted_at" => null
        ])->get();
        if(count($find)){
            foreach($find as $key => $idesign){
                Instructional_Design::where('id',$idesign->id)
                ->update([
                    "objectives" => json_encode($request->objectives[$key]),
                    "section_objective" => $request->section_objective[$key],
                    "instructors" => json_encode($request->instructors[$key]),
                    "video_length" => $request->video_length[$key],
                    "video_counter" => $request->video_counter[$key],
                    "article_counter" => $request->article_counter[$key],
                    "evaluation_quiz_count" => $request->evaluation_quiz_count[$key],
                    "evaluation_question_count" => $request->evaluation_question_count[$key],
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
            }
            
        }else{

            for($i=0;$i<count($request->objectives);$i++){
                $find = new Instructional_Design;
                $find->type = "course";
                $find->data_id = $id;
                $find->objectives = json_encode($request->objectives[$i]);
                $find->section_objective = $request->section_objective[$i];
                $find->instructors = json_encode($request->instructors[$i]);
                $find->video_length = $request->video_length[$i];
                $find->video_counter = $request->video_counter[$i];
                $find->article_counter = $request->article_counter[$i];
                $find->evaluation_quiz_count = $request->evaluation_quiz_count[$i];
                $find->evaluation_question_count = $request->evaluation_question_count[$i];
                $find->created_by = Auth::user()->id;
                $find->created_at = date("Y-m-d H:i:s");
                $find->save();
            } 
        }
        _notification_insert(
            "course_creation",
            Auth::user()->id,
            _current_course()->id,
            "Course Submission for review",
            "You're almost done. Submit your "._current_course()->title."'s link and share the email & password for evaluators",
            "/provider/courses"

        );
        foreach(json_decode(_current_course()->instructor_id) as $inst){
            if(Auth::user()->id != $inst){
                _notification_insert(
                    "course_creation",
                    $inst,
                    _current_course()->id,
                    "Course Submission for review",
                    _current_course()->title." will now be submitted for accreditation.",
                    "/provider/courses"
                );
            }
            
        }

        

        $course = Course::find($id);

        foreach(json_decode($course->profession_id) as $prof_id){
            $accreditation[] = array(
                'id'=> $prof_id,
                'units'=> "",
                'program_no'=> "",
                
            );
        }
        $course->accreditation = json_encode($accreditation);
        $course->prc_status = "approved";
        $course->approved_at = date("Y-m-d H:i:s");
        if($course->submit_accreditation_evaluation==null){
            $acc_email = "accreditor_".date("Ymd-").uniqid()."@fastcpd.com";
            $random_password = [
                "FASTCPD_ACCREDITOR_{$id}_".uniqid(), 
                "{$id}_".uniqid()."_FASTCPD_ACCREDITOR", 
                "FASTCPD_{$id}_".uniqid()."_ACCREDITOR", 
                "FASTCPD_{$id}_ACCREDITOR_".uniqid(), 
                "{$id}_FASTCPD_ACCREDITOR_".uniqid(), 
                "FASTCPD_ACCREDITOR_".uniqid().$id,
            ];
            $acc_password = $random_password[rand(0,5)];

            User::insert([
                'name' => "Accreditor for "._current_course()->title,
                'email' => $acc_email,
                'accreditor' => 'active',
                'password' => bcrypt($acc_password),
                'status' => 'active',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]); 
    
            $course->submit_accreditation_evaluation = json_encode([
                "evaluation" => true,
                "email" => $acc_email,
                "password" => $acc_password
            ]);
            $app_url = \config('app.link') . "/data/pdf/user/certificate/" . $id;
            $course->certificate_templates = json_encode([
                \config('app.link') . "/data/pdf/user/certificate/" . $id
            ]);
        }else{
            $current_evaluation = json_decode($course->submit_accreditation_evaluation);
            $current_evaluation->evaluation = true;

            $course->submit_accreditation_evaluation = json_encode($current_evaluation);
        }
        $course->save();



        return response()->json(["status" => 200, "id" => $id]);
    }

    function SessionAccreditation(Request $request) : JsonResponse{

        $request->session()->put("evaluation",$request->evaluation);
        
        return response()->json(["status" => 200]);
    }

    function removeCoursePV(Request $request) : JsonResponse
    {
        $type = $request->type;
        $course = Course::find(_current_course()->id);

        switch ($type) {
            case 'poster':
                $course->course_poster = null;
                /**
                 * remove file from s3 bucket
                 * 
                 */
                $explode = explode("/", _current_course()->course_poster);
                $name = end($explode);
                $s3 = Storage::disk('s3');
                $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$name}";
                if($s3->delete($filepath)){
                    $course->save();
                    return response()->json([]);
                }
                break;

            case 'video':
                $course->course_video = null;
                /**
                 * remove file from s3 bucket
                 * 
                 */
                $explode = explode("/", _current_course()->course_video);
                $name = end($explode);
                $s3 = Storage::disk('s3');
                $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$name}";
                $s3->delete($filepath);  
                $course->save();
                return response()->json([]);
                
                break;
        }
        return response()->json([], 422);
    }

    protected function paginatedQuery(Request $request)
    {  
        $id = _current_course()->id;
        $provider_id = _current_provider()->id;
        $instructor = $request->session()->get('filter_instructor');
        
        if($instructor){
            if($instructor[0] == "all"){
                $instructor_list = User::select("users.id as user_id","users.name",
                                                "instructor_permissions.id as permission_id","instructor_permissions.course_details","instructor_permissions.attract_enrollments","instructor_permissions.instructors",
                                                "instructor_permissions.video_and_content","instructor_permissions.handouts","instructor_permissions.grading_and_assessment","instructor_permissions.submit_for_accreditation",
                                                "instructor_permissions.price_and_publish","instructors.status")
                                    ->where("instructor_permissions.course_id",$id)
                                    ->leftJoin("instructors","instructors.user_id","users.id")
                                    ->leftJoin("instructor_permissions","users.id","instructor_permissions.user_id")
                                    ->where("instructors.provider_id",$provider_id)
                                    ->get();
            }else{
                $instructor_list = User::select(
                    "users.id as user_id","users.name",
                    "instructor_permissions.id as permission_id","instructor_permissions.course_details","instructor_permissions.attract_enrollments","instructor_permissions.instructors",
                    "instructor_permissions.video_and_content","instructor_permissions.handouts","instructor_permissions.grading_and_assessment","instructor_permissions.submit_for_accreditation",
                    "instructor_permissions.price_and_publish","instructors.status"
                )
                ->whereIn("users.id",$instructor)
                ->where("instructor_permissions.course_id",$id)
                ->where("instructors.provider_id",$provider_id)
                ->where("instructors.status", "!=", "delete")
                ->leftJoin("instructors","instructors.user_id","users.id")
                ->leftJoin("instructor_permissions","users.id","instructor_permissions.user_id")
                ->get();
            }

            $instructor_list = array_filter($instructor_list->toArray(), function($use) use($provider_id){
                $is_provider_inst = _is_provider_instructor($use["user_id"], $provider_id);
                if($is_provider_inst){
                    return [
                        "user_id" => $use['user_id'],
                        "course_id" => _current_course()->id,
                        "name" => $use['name'],
                        "course_details" => $use ? $use['course_details'] : 0,
                        "attract_enrollments" => $use ? $use['attract_enrollments'] : 0,
                        "instructor_permission" => $use ? $use['instructors'] : 0,
                        "video_and_content" => $use ? $use['video_and_content'] : 0 ,
                        "handouts" => $use ? $use['handouts'] : 0 ,
                        "grading_and_assessment" => $use ? $use['grading_and_assessment'] : 0 ,
                        "submit_for_accreditation" => $use ? $use['submit_for_accreditation'] : 0 ,
                        "price_and_publish" => $use ? $use['price_and_publish'] : 0 ,
                        "status" => $use['status'],
                        "co_provider" => _is_co_provider($use["user_id"], $provider_id) ? "disabled" : ""
                    ];
                }
            });
        }else{
            if($id){
                $course = Course::find($id);
                if($course->instructor_id){
                    $instructor_list = User::select(
                        "users.id as user_id","users.name",
                        "instructor_permissions.id as permission_id","instructor_permissions.course_details","instructor_permissions.attract_enrollments","instructor_permissions.instructors",
                        "instructor_permissions.video_and_content","instructor_permissions.handouts","instructor_permissions.grading_and_assessment","instructor_permissions.submit_for_accreditation",
                        "instructor_permissions.price_and_publish","instructors.status"
                    )
                    ->whereIn('instructors.user_id',json_decode($course->instructor_id))
                    ->where("instructor_permissions.course_id",$id)
                    ->where("instructors.provider_id",$provider_id)
                    ->where("instructors.status", "!=", "delete")
                    ->leftJoin("instructors","instructors.user_id","users.id")
                    ->leftJoin("instructor_permissions","instructor_permissions.user_id","users.id")
                    ->get();
                    
                    $instructor_list = array_filter($instructor_list->toArray(), function($use) use($provider_id){
                        $is_provider_inst = _is_provider_instructor($use["user_id"], $provider_id);
        
                        if($is_provider_inst){
                            return [
                                "user_id" => $use['user_id'],
                                "course_id" => _current_course()->id,
                                "name" => $use['name'],
                                "course_details" => $use ? $use['course_details'] : 0,
                                "attract_enrollments" => $use ? $use['attract_enrollments'] : 0,
                                "instructor_permission" => $use ? $use['instructors'] : 0,
                                "video_and_content" => $use ? $use['video_and_content'] : 0 ,
                                "handouts" => $use ? $use['handouts'] : 0 ,
                                "grading_and_assessment" => $use ? $use['grading_and_assessment'] : 0 ,
                                "submit_for_accreditation" => $use ? $use['submit_for_accreditation'] : 0 ,
                                "price_and_publish" => $use ? $use['price_and_publish'] : 0 ,
                                "status" => $use['status'],
                                "co_provider" => _is_co_provider($use["user_id"], $provider_id) ? "disabled" : ""
                            ];
                        }
                    });
                }else{
                    $instructor_list = [];
                }
                
                if($instructor === 0){
                    $instructor_list = [];
                }
                
            }else{
                $instructor_list = [];
            }
        }
      
        $totalIds = array_map(function($user){
            return $user['user_id'];
        }, $instructor_list);

        $paramFilter = $request->session()->get("user_query");
        $paramTable = $request->all();

        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        $quotient = floor(count($instructor_list) / $perPage);
        $reminder = (count($instructor_list) % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        return array(
            "meta"=> array(
                "page"=> $paramTable["pagination"]["page"] ?? '1',
                "pages"=> $paramTable["pagination"]["pages"] ?? $pagesScold,
                "perpage"=> $perPage,
                "total"=> count($instructor_list),
                "rowIds"=> $totalIds,
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> $instructor_list,
        );
    }

    function FilterInstructor(Request $request){

        if($request->instructor){
            $provider_id = _current_provider()->id;
            if($request->instructor[0] == "all") {
                $instructor_list = Instructor::where("provider_id",$provider_id)->get();
                foreach($instructor_list as $list){
                        $permission = Instructor_Permissions::where(["course_id" => session('course_id'),"user_id" => $list->user_id])->first();
                        if(! $permission){
                                Instructor_Permissions::insert([
                                    "user_id" =>  $list->user_id,
                                    "course_id" => _current_course()->id,
                                    "course_details" => 1,
                                    "attract_enrollments" => 1,
                                    "instructors" => 0,
                                    "video_and_content" => 1,
                                    "handouts" => 1,
                                    "grading_and_assessment" => 1,
                                    "submit_for_accreditation" => 1,
                                    "price_and_publish" => 1,
                                    "created_by" => Auth::user()->id,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_by" => Auth::user()->id,
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                        }
                }
            }else{
                foreach($request->instructor as $perm ){
                    $permission = Instructor_Permissions::where(["course_id" => session('course_id'),"user_id" => $perm])->first();
                    if(! $permission){
                            if(_is_co_provider($perm) != false){
                                Instructor_Permissions::insert([
                                    "user_id" => $perm,
                                    "course_id" => _current_course()->id,
                                    "course_details" => 1,
                                    "attract_enrollments" => 1,
                                    "instructors" => 1,
                                    "video_and_content" => 1,
                                    "handouts" => 1,
                                    "grading_and_assessment" => 1,
                                    "submit_for_accreditation" => 1,
                                    "price_and_publish" => 1,
                                    "created_by" => Auth::user()->id,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_by" => Auth::user()->id,
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                            }else{
                                Instructor_Permissions::insert([
                                    "user_id" => $perm,
                                    "course_id" => _current_course()->id,
                                    "course_details" => 1,
                                    "attract_enrollments" => 1,
                                    "instructors" => 0,
                                    "video_and_content" => 1,
                                    "handouts" => 1,
                                    "grading_and_assessment" => 1,
                                    "submit_for_accreditation" => 0,
                                    "price_and_publish" => 0,
                                    "created_by" => Auth::user()->id,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_by" => Auth::user()->id,
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                            }
                            
                    }
                }
            }
        }else{
            $request->instructor = 0;

            if(!_is_co_provider(_current_provider()->id)){
                if(_is_provider_instructor(Auth::user()->id, _current_provider()->id)){
                    return response()->json(["status" => 500, "message" => "You can't remove all instructors! Please leave at least one(1) instructor record"]);
                }
            }
        }
        
        $request->session()->put("filter_instructor",$request->instructor);

        $id = _current_course()->id;
        $instructor_id = _current_course()->instructor_id;
        $collection =[];
        $removed =[];
        $added =[];
        $selected_instructors = $request->session()->get("filter_instructor");

        $course = Course::find($id);
        if($course){
            if($selected_instructors){
                if($selected_instructors[0] == "all"){
                    $get_users = Instructor::where("provider_id", _current_provider()->id)->get();
                    
                    $instructor = [];
                    foreach($get_users as $user){
                        $instructor[] = $user->id;
                    }
                }else{
                    $instructor = [$selected_instructors];
                }

                foreach($instructor as $new_ins){
                    if (!in_array($new_ins, $collection)){ 
                        $collection[] = $new_ins; 
                    }
                }
                /////////////////checks who has been removed
                if($course->instructor_id == null){

                    foreach($collection[0] as $inst_id){
                        $instructor_info = User::find($inst_id);
                        if($inst_id != Auth::user()->id){
                            _notification_insert(
                                "course_creation",
                                $inst_id,
                                $id,
                                "Course Invitation as an instructor",
                                "You have been invited to access ".$course->title." by " . Auth::user()->name,
                                "/provider/course/" . $id

                            );
                            _notification_insert(
                                "course_creation",
                                Auth::user()->id,
                                $id,
                                "Course Invitation to an instructor",
                                "You have invited ".$instructor_info->name." to access " . $course->title,
                                "/provider/course/" . $id
    
                            );
                            _send_notification_email($instructor_info->email,"instructor_course_invitation",$id,$instructor_info->id);
                        }else{
                            _notification_insert(
                                "course_creation",
                                Auth::user()->id,
                                $id,
                                "Course Invitation to an instructor",
                                "You have invited your account to access " . $course->title,
                                "/provider/course/" . $id
    
                            );
                        }
                        
                    }

                }else{
                    if(count($collection[0]) > count(json_decode($course->instructor_id))){
                        $added = array_diff($collection[0], json_decode($course->instructor_id) );
                        foreach($added as $inst_id){
                            $instructor_info = User::find($inst_id);
                            
                            if($inst_id != Auth::user()->id){
                                _notification_insert(
                                    "course_creation",
                                    $inst_id,
                                    $id,
                                    "Course Invitation as an instructor",
                                    "You have been invited to access ".$course->title." by " . Auth::user()->name,
                                    "/provider/course/" . $id
    
                                );
                                _notification_insert(
                                    "course_creation",
                                    Auth::user()->id,
                                    $id,
                                    "Course Invitation to an instructor",
                                    "You have invited ".$instructor_info->name." to access " . $course->title,
                                    "/provider/course/" . $id
        
                                );
                                _send_notification_email($instructor_info->email,"instructor_course_invitation",$id,$instructor_info->id);
                            }else{
                                _notification_insert(
                                    "course_creation",
                                    Auth::user()->id,
                                    $id,
                                    "Course Invitation to an instructor",
                                    "You have invited your account to access " . $course->title,
                                    "/provider/course/" . $id
        
                                );
                            }
                        }
                    }else{
                        $removed = array_diff(json_decode($course->instructor_id) ,$collection[0] );

                        foreach($removed as $inst_id){
                            $instructor_info = User::find($inst_id);

                            if($inst_id != Auth::user()->id){
                                _notification_insert(
                                    "course_creation",
                                    $inst_id,
                                    $id,
                                    "Unassigned instructor to course",
                                    "Your access to ".$course->title." has been edited." ,
                                    "/provider/course/" . $id
    
                                );
    
                                _notification_insert(
                                    "course_creation",
                                    Auth::user()->id,
                                    $id,
                                    "Unassigned instructor to course",
                                    "You've edited access of ".$instructor_info->name." to " . $course->title,
                                    "/provider/course/" . $id
        
                                );
                                // instructor_course_update
                                _send_notification_email($instructor_info->email,"instructor_course_unassigned",$id,$instructor_info->id);
                            }else{
                                _notification_insert(
                                    "course_creation",
                                    Auth::user()->id,
                                    $id,
                                    "Unassigned instructor to course",
                                    "You've edited your own access to " . _current_course()->title,
                                    "/provider/course/" . $id
                        
                                );
                            }
                        }

                    }

                }
                $course->instructor_id = json_encode($collection[0]);

                //////////////////////////////////////////////end
                if ($course->save()){
                    return response()->json(["status" => 200, "message" => "Instructor added!"]);
                }
                return response()->json(["status" => 500, "message" => "Unable to save update! Please refresh your browser"]);
            }else{
                $course->instructor_id = null;
                if ($course->save()){
                    return response()->json(["status" => 200, "message" => "Instructor list updated!"]);
                }
                return response()->json(["status" => 500, "message" => "Unable to save update! Please refresh your browser"]);
            }
        }else{
            return response()->json(["status" => 500, "message" => "Course not Found!"]);
        }
    }

    function InstructorPermission(Request $request) : JsonResponse{
        $id = _current_course()->id;
        $instructor_id = _current_course()->instructor_id;
        $collection =[];
        if($id){
            if($request->instructors){
                if($request->instructors[0] == "all"){
                    $get_users = Instructor::where("provider_id", _current_provider()->id)->get();
                    
                    $instructor = [];
                    foreach($get_users as $user){
                        $instructor[] = $user->id;
                    }
                }else{
                    $instructor = [$request->instructors];
                }
                if($instructor_id){
                    foreach(json_decode($instructor_id) as $ins){
                        $collection[] = $ins;
                    }
                }
                
                foreach($instructor as $new_ins){
                    if (!in_array($new_ins, $collection)){ 
                        $collection[] = $new_ins; 
                    } 
                }
                $course = Course::find($id);
                $course->instructor_id = json_encode($collection);

                if ($course->save()){
                    return response()->json(["status" => 200, "message" => "Instructor Added!"]);
                }

                return response()->json(["status" => 500, "message" => "Something went wrong!"]);
            }
        }else{
            return response()->json(["status" => 500, "message" => "Course not Found!"]);
        }
    }

    function UpdatePermission(Request $request) {
        $status = 0;
        $id = _current_course()->id;
        if($request->status == "true"){
            $status = 1;
        }

        Instructor_Permissions::where('course_id', $id)
                ->where('user_id', $request->user_id)
                ->update([$request->column => $status]);
        $instructor_info = User::find($request->user_id);
        
        if($request->user_id != Auth::user()->id){
            //instructor
            _notification_insert(
                "course_creation",
                $request->user_id,
                $id,
                "Unassigned instructor to course",
                "Your access to "._current_course()->title." has been edited." ,
                "/provider/course/" . $id
    
            );
            
            _notification_insert(
                "course_creation",
                Auth::user()->id,
                $id,
                "Unassigned instructor to course",
                "You've edited access of ".$instructor_info->name." to " . _current_course()->title,
                "/provider/course/" . $id
    
            );
        }else{
            _notification_insert(
                "course_creation",
                Auth::user()->id,
                $id,
                "Unassigned instructor to course",
                "You've edited your own access to " . _current_course()->title,
                "/provider/course/" . $id
    
            );
        }
        
        
    }

    function AllowHandouts(Request $request) : JsonResponse
    {
        $id = _current_course()->id;
        $allow = $request->allow;
        if($id){
            $course = Course::find($id);
            $course->allow_handouts = $allow=="true"?1:0;
            $course->save();
            return response()->json([]);
        }

        return response()->json([], 422);
    }

    function ShowHandouts(Request $request) : JsonResponse
    {
        $id = _current_course()->id;
        if(_current_course()->allow_handouts){
                $data = Handout::where([
                    "type" => "course",
                    "data_id" => $id,
                    "deleted_at" => null
                ])->get();
    
                return response()->json(["data" => $data, "allow" => true]);
        }

        return response()->json(["allow"=>false]);
    }

    function StoreHandouts(Request $request) : JsonResponse{
        $id = _current_course()->id;
        $handouts = $request->handouts;
        if($id){
            $handouts = array_map(function($hnd) use($id){
                $hnd["type"] = "course";
                $hnd["data_id"] = $id;
                $hnd["created_at"] = date("Y-m-d H:i:s");
                $hnd["created_by"] = Auth::user()->id;

                return $hnd;
            }, $handouts);

            Handout::insert($handouts);
            return response()->json(["status" => 200 , "message" => "Handouts successfully saved!"]);
        }
        
        return response()->json(["status" => 500, "message" => "Course not Found! Please refresh your browser" ]);

    }

    function RemoveHandouts(Request $request) : JsonResponse{
        $course = _current_course()->id;
        $id = $request->id;
        if($course){
            $handout = Handout::find($id);
            $handout->deleted_at = date("Y-m-d H:i:s");

            if($handout->save()){

                /**
                 * remove file from s3 bucket
                 * 
                 */
                $explode = explode("/", $handout->url);
                $name = end($explode);
                $s3 = Storage::disk('s3');
                $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/handouts/{$name}";
                if($s3->delete($filepath)){
                    return response()->json([]);
                }
            }
        }

        return response()->json([], 422);
    }

    function StoreGradingAssessment(Request $request) : JsonResponse{
        $id = _current_course()->id;
        if($id){
            $percentage = $request->percentage ?? 0;
            $retry = $request->retry;
            $assessment = $request->assessment;

            $assess = [
                "assessment" => $assessment,
                "retry" => $retry,
                "percentage" => ($percentage/100)
            ];
            
            $response = Course::where("id",$id)->update([
                "allow_retry" => $retry,
                "pass_percentage" => ($percentage/100),
                "assessment" => json_encode($assess),
            ]);
            
            if($response){
                return response()->json(["status" => 200 , "message" => "Grading and Assessment successfully updated!"]);
            }else{
                return response()->json(["status" => 500 , "message" => "Something went wrong"]);
            }
           
        }else{
            return response()->json(["status" => 500, "message" => "Course Not Found" ]);
        }

        
    }

    function StoreAccreditation(Request $request) : JsonResponse{
        $id = _current_course()->id;
        if($id){
            $price = $request->price ?? 0;
            $evaluation = (int)$request->evaluation;

            $response = Course::find($id);
            $response->price = $request->price;
            $response->target_number_students = $request->target_number_students;

            if($response->save()){
                return response()->json(["status" => 200 , "message" => "Price and Target Students successfully saved!"]);
            }else{
                return response()->json(["status" => 500 , "message" => "Something went wrong! Please refresh your browser"]);
            }
        }else{
            return response()->json(["status" => 500, "message" => "Course not found! Please try again later" ]);
        }
    }

    function StorePricePublish(Request $request) : JsonResponse{
        $id = _current_course()->id;
        if($id){
            $published_date =  date("Y-m-d H:i:s", strtotime($request->published_date));
            $date_approved =  date("Y-m-d H:i:s", strtotime($request->date_approved));
            $start_date = date("Y-m-d H:i:s", strtotime($request->start_date));
            $end_date = date("Y-m-d H:i:s", strtotime($request->end_date));

            $course = Course::find($id);
            $course->published_at = $published_date;
            $course->approved_at = $date_approved;
            $course->accreditation = $request->accreditation;
            $course->session_start = $start_date;
            $course->session_end = $end_date;

            /**
             * temporary
             * 
             */
            $course->program_accreditation_no = $request->accreditation[0]["program_no"];
            $course->total_unit_amounts = $request->accreditation[0]["units"];

            if($course->save()){
                return response()->json(["status" => 200, "message" => "Course has been successfully updated for Publish!"]);
            }
            return response()->json(["status" => 500, "message" => "Unable to publish course! Please try again later"]);
        }else{
            return response()->json(["status" => 500, "message" => "Course not found! Please refresh your browser"]);
        }
    }
   
    function check_accreditors_account(){
        $id = _current_course()->id;

        if($id){
            $course = Course::find($id);
            $user = new User;

            $user->name ="Accreditor ".strtoupper($course->title);
            $user->email = "accreditor".strtoupper($course->title)."".$course->provider_id."".date("Y")."@fastcpd.com";
            $user->user_name = "accreditor".strtoupper($course->title)."".$course->provider_id."".date("Y");
            $user->password = bcrypt($course->title."".$course->id."".date("Y"));

            // Comment if no email-verification
            $user->status = "inactive";
            $user->added_by = Auth::user()->id;
        }
    }

    function StoreVideoContentSection(Request $request):JsonResponse {
        $id = _current_course()->id;
        $section_name = $request->section_name;
        $objective = $request->objective;
        $section_id = $request->section_id;

        if($id){
            $section_exist = Section::where(["section_number" => $section_id, "type"=>"course", "data_id"=> $id, "deleted_at"=>null])->first();
            if($section_exist){
                $section = Section::find($section_exist->id);
                $section->type = "course";
                $section->data_id = $id;
                $section->section_number = $section_id;
                $section->name = $section_name;
                $section->objective = $objective;
                $section->created_at = date("Y-m-d H:i:s");
                $section->updated_at = date("Y-m-d H:i:s");
                $section->created_by = Auth::user()->id;
                $section->save();
            }else{
                $section = new Section;
                $section->type = "course";
                $section->data_id = $id;
                $section->section_number = $section_id;
                $section->name = $section_name;
                $section->objective = $objective;
                $section->created_at = date("Y-m-d H:i:s");
                $section->updated_at = date("Y-m-d H:i:s");
                $section->created_by = Auth::user()->id;
                $section->save();
            }
           
            if($section){
                $message = "Section details is ".($section_exist ? "updated" : "added");
                return response()->json(["status" => 200, "message" => $message, "exist"=>$section_exist ? true : false]);
            }else{
                return response()->json(["status" => 500, "message" => "Something went wrong! Please try again later"]);
            }
          
        }else{
            return response()->json(["status" => 500, "message" => "Course not found! Please refresh your browser"]);
        }
    }

    function store_video(Request $request) : JsonResponse
    {
        $video_id = $request->video_id;
        $title = $request->title;
        $section = (int)$request->section;
        $part = (int)$request->part;

        $current_number_part = $request->current_number_part;
        $number_of_parts = $request->number_of_parts;

        $sec = Section::where([
            "type"=>"course", "data_id"=>  _current_course()->id,
            "section_number" => $section,
            "deleted_at" => null,
        ])->first();

        if($sec){
            $duration = 0;

            if($video_id){
                $video = Video::find($video_id);
                $video->title = $title;
                $video->section_id = $sec->id;
                $video->poster = URL::to('/img/sample/poster-sample.png');
                $video->updated_at = date("Y-m-d H:i:s");
                $video->sequence_number = $current_number_part;
            }else{
                $video = new Video;
                $video->title = $title;
                $video->section_id = $sec->id;
                $video->poster = URL::to('/img/sample/poster-sample.png');
                $video->created_at = date("Y-m-d H:i:s");
                $video->updated_at = date("Y-m-d H:i:s");
                $video->created_by = Auth::user()->id;
                $video->sequence_number = $current_number_part;
            }
            
            if($video->save()){
                /**
                 * Replacement of sequence in section
                 * 
                 */
                if($sec->sequences){
                    $original_sequence = json_decode($sec->sequences);

                    $renew_sequence = [];
                    $recurring_part = 0;
                    $newpart = true;
                    foreach ($original_sequence as $index => $original) {
                        $recurring_part++;
                        if($original->part == $current_number_part){
                            if($original->id == $video->id && $original->type == "video"){
                                $renew_sequence[] = [
                                    "id" => $video->id,
                                    "part" => $recurring_part,
                                    "type" => "video",
                                ];
                                $newpart = false;
                            }else{
                                $renew_sequence[] = [
                                    "id" => $video->id,
                                    "part" => $recurring_part,
                                    "type" => "video",
                                ];
                                $recurring_part++;
                                $renew_sequence[] = [
                                    "id" => $original->id,
                                    "part" => $recurring_part,
                                    "type" => $original->type,
                                ];
                                $newpart = false;
                            }
                        }else{
                            $renew_sequence[] = [
                                "id" => $original->id,
                                "part" => $recurring_part,
                                "type" => $original->type,
                            ];
                        }
                    }

                    if($newpart){
                        $renew_sequence[] = [
                            "id" => $video->id,
                            "part" => $current_number_part,
                            "type" => "video",
                        ];
                    }

                    $sec->sequences = json_encode($renew_sequence);
                    $sec->save();
                }else{
                    $sec->sequences = json_encode([[
                        "part" => $current_number_part,
                        "type" => "video",
                        "id" => $video->id,
                    ]]);
                    $sec->save();
                }

                return response()->json(["status" => 200, "message" => "Video title has been saved!", "data"=>["url"=>$video->cdn_url!=null?true:false,"video_id"=>$video->id]]);
            }
            
            return response()->json(["status" => 500, "message" => "Unable to save video title! Please try again later."]);
        }

        return response()->json(["status" => 500, "message" => "Section not Found!"]);
    }

    function store_article(Request $request) : JsonResponse
    {
        $article_id = $request->article_id;
        $title = $request->title;
        $description = $request->textarea;
        $section = (int)$request->section;
        $part = (int)$request->part;

        $current_number_part = (int)$request->current_number_part;
        $number_of_parts = (int)$request->number_of_parts;

        $sec = Section::where([
            "type"=>"course", "data_id"=> _current_course()->id,
            "section_number" => $section,
            "deleted_at" => null,
        ])->first();

        if($sec){
            $read_time = _estimated_reading_time($description);

            if($article_id){
                $article = Article::find($article_id);
                $article->section_id = $sec->id;
                $article->sequence_number = $current_number_part;
                $article->title = $title;
                $article->description = $description;
                $article->reading_time = $read_time;
                $article->updated_at = date("Y-m-d H:i:s");
            }else{
                $article = new Article;
                $article->section_id = $sec->id;
                $article->sequence_number = $current_number_part;
                $article->title = $title;
                $article->description = $description;
                $article->reading_time = $read_time;
                $article->created_by = Auth::user()->id;
                $article->created_at = date("Y-m-d H:i:s");
                $article->updated_at = date("Y-m-d H:i:s");
            }
            
            if($article->save()){
                /**
                 * Replacement of sequence in section
                 * 
                 */
                if($sec->sequences){
                    $original_sequence = json_decode($sec->sequences);

                    $renew_sequence = [];
                    $recurring_part = 0;
                    $newpart = true;
                    foreach ($original_sequence as $index => $original) {
                        $recurring_part++;
                        if($original->part == $current_number_part){
                            if($original->id == $article->id && $original->type == "article"){
                                $renew_sequence[] = [
                                    "id" => $article->id,
                                    "part" => $recurring_part,
                                    "type" => "article",
                                ];
                                $newpart = false;
                            }else{
                                $renew_sequence[] = [
                                    "id" => $article->id,
                                    "part" => $recurring_part,
                                    "type" => "article",
                                ];
                                $recurring_part++;
                                $renew_sequence[] = [
                                    "id" => $original->id,
                                    "part" => $recurring_part,
                                    "type" => $original->type,
                                ];
                                $newpart = false;
                            }
                        }else{
                            $renew_sequence[] = [
                                "id" => $original->id,
                                "part" => $recurring_part,
                                "type" => $original->type,
                            ];
                        }
                    }

                    if($newpart){
                        $renew_sequence[] = [
                            "id" => $article->id,
                            "part" => $current_number_part,
                            "type" => "article",
                        ];
                    }

                    $sec->sequences = json_encode($renew_sequence);
                    $sec->save();
                }else{
                    $sec->sequences = json_encode([[
                        "part" => $current_number_part,
                        "type" => "article",
                        "id" => $article->id,
                    ]]);
                    $sec->save();
                }

                return response()->json(["status" => 200, "message" => "Article has been saved!", "data"=>["article_id"=>$article->id]]);
            }
            
            return response()->json(["status" => 500, "message" => "Unable to save article! Please try again later."]);
        }

        return response()->json(["status" => 500, "message" => "Section not Found!"]);
    }

    function store_quiz(Request $request) : JsonResponse
    {
        $quiz_id = $request->quiz_id;
        $title = $request->title;
        $section = (int)$request->section;
        $part = (int)$request->part;

        $current_number_part = (int)$request->current_number_part;
        $number_of_parts = (int)$request->number_of_parts;

        $sec = Section::where([
            "type"=>"course", "data_id"=> _current_course()->id,
            "section_number" => $section,
            "deleted_at" => null,
        ])->first();

        if($sec){
            if($quiz_id){
                $quiz = Quiz::find($quiz_id);
                $quiz->section_id = $sec->id;
                $quiz->sequence_number = $current_number_part;
                $quiz->title = $title;
                $quiz->updated_at = date("Y-m-d H:i:s");
            }else{
                $quiz = new Quiz;
                $quiz->section_id = $sec->id;
                $quiz->sequence_number = $current_number_part;
                $quiz->title = $title;
                $quiz->created_by = Auth::user()->id;
                $quiz->created_at = date("Y-m-d H:i:s");
                $quiz->updated_at = date("Y-m-d H:i:s");
            }
            
            if($quiz->save()){
                /**
                 * Replacement of sequence in section
                 * 
                 */
                if($sec->sequences){
                    $original_sequence = json_decode($sec->sequences);

                    $renew_sequence = [];
                    $recurring_part = 0;
                    $newpart = true;
                    foreach ($original_sequence as $index => $original) {
                        $recurring_part++;
                        if($original->part == $current_number_part){
                            if($original->id == $quiz->id && $original->type == "quiz"){
                                $renew_sequence[] = [
                                    "id" => $quiz->id,
                                    "part" => $recurring_part,
                                    "type" => "quiz",
                                ];
                                $newpart = false;
                            }else{
                                $renew_sequence[] = [
                                    "id" => $quiz->id,
                                    "part" => $recurring_part,
                                    "type" => "quiz",
                                ];
                                $recurring_part++;
                                $renew_sequence[] = [
                                    "id" => $original->id,
                                    "part" => $recurring_part,
                                    "type" => $original->type,
                                ];
                                $newpart = false;
                            }
                        }else{
                            $renew_sequence[] = [
                                "id" => $original->id,
                                "part" => $recurring_part,
                                "type" => $original->type,
                            ];
                        }
                    }

                    if($newpart){
                        $renew_sequence[] = [
                            "id" => $quiz->id,
                            "part" => $current_number_part,
                            "type" => "quiz",
                        ];
                    }

                    $sec->sequences = json_encode($renew_sequence);
                    $sec->save();
                }else{
                    $sec->sequences = json_encode([[
                        "part" => $current_number_part,
                        "type" => "quiz",
                        "id" => $quiz->id,
                    ]]);
                    $sec->save();
                }

                $no_items = Quiz_Item::where([
                    "quiz_id" => $quiz->id,
                    "deleted_at" => null,
                ])->get()->count();

                return response()->json(["status" => 200, "message" => "Quiz has been saved!", "data"=>["quiz_id"=>$quiz->id, "items" => $no_items]]);
            }
            
            return response()->json(["status" => 500, "message" => "Unable to save quiz! Please try again later."]);
        }

        return response()->json(["status" => 500, "message" => "Section not Found!"]);
    }

    function store_quiz_item(Request $request) : JsonResponse
    {
        $quiz_id = $request->quiz_id;
        $quiz_item_id = $request->quiz_item_id;
        $question = $request->question;
        $choices = $request->choices;
        $answer = $request->answer;
        $section = (int)$request->section;
        $part = (int)$request->part;

        $current_number_part = (int)$request->current_number_part;
        $number_of_parts = (int)$request->number_of_parts;

        $section_data = Section::where([
            "type"=>"course", "data_id"=> _current_course()->id,
            "section_number" => $section,
            "deleted_at" => null
        ])->first();

        if($section_data){
            $quiz = Quiz::where([ 
                "id" => $quiz_id,
                "deleted_at" => null,
            ])->first();
    
            if($quiz){
                $renew_choices = [];
                $total_reading_time = 0;
                foreach ($choices as $index => $choice) {
                    $total_reading_time += _estimated_reading_time($choice['description']);
                    if($index == $answer){
                        $renew_choices[] = [
                            "choice" => $choice['description'],
                            "explain" => $choice['explanation'] ?? null,
                            "answer" => true,
                        ];
                    }else{
                        $renew_choices[] = [
                            "choice" => $choice['description'],
                            "explain" => $choice['explanation'] ?? null,
                            "answer" => false,
                        ];
                    }
                }
    
                $quiz_item = Quiz_Item::where([
                    "id" => $quiz_item_id,
                    "quiz_id" => $quiz_id,
                    "deleted_at" => null,
                ])->first();      
    
                if($quiz_item){
                    $quiz_item->question = $question;
                    $quiz_item->choices = json_encode($renew_choices);
                    $quiz_item->answer = $answer;
    
                }else{
                    $quiz_item = new Quiz_Item;
                    $quiz_item->quiz_id = $quiz_id;
                    $quiz_item->question = $question;
                    $quiz_item->choices = json_encode($renew_choices);
                    $quiz_item->answer = $answer;
                    $quiz_item->created_by = Auth::user()->id;
                }
    
                if($quiz_item->save()){
                    $quiz->reading_time = $total_reading_time;
                    $quiz->save();
    
                    return response()->json(["status" => 200, "message" => "Quiz Item has been saved!", "data"=>["quiz_item"=>$quiz_item]]);
                }
    
                return response()->json(["status" => 500, "message" => "Unable to save quiz item! Please try again later."]);
            }

            return response()->json(["status" => 500, "message" => "Quiz not Found! Please refresh your browser"]);
        }
        return response()->json(["status" => 500, "message" => "Section not Found! Please refresh your browser"]);
    }

    function upload_textarea_image(Request $request) : JsonResponse
    {
        $image = $request->image;
        $filepath = "courses/images/textarea/provider".(_current_provider()->id)."/course".(_current_course()->id)."/".time().$image->getClientOriginalName();
        $path = Storage::disk('s3')->put($filepath,file_get_contents($image));
        if($path){
            $pathname = Storage::disk('s3')->url($filepath);
            return response()->json(["image_url" => $pathname]);
        }
    }

    function remove_section(Request $request) : JsonResponse
    {
        $section_number = $request->section;
        $section = Section::where([
            "type"=>"course", "data_id"=> _current_course()->id,
            "section_number" => $section_number,
            "deleted_at" => null
        ])->first();

        if($section){
            $section->deleted_at = date("Y-m-d H:i:s");
            if($section->sequences!=null){
                foreach (json_decode($section->sequences) as $key => $seq) {
                    switch ($seq->part) {
                         case 'video':
                             $video = Video::find($seq->id);
                             if($video){
                                 $video->deleted_at = date("Y-m-d H:i:s");
                                 $video->save();
                             }
                             break;
     
                         case 'article':
                             $article = Article::find($seq->id);
                             if($article){
                                 $article->deleted_at = date("Y-m-d H:i:s");
                                 $article->save();
                             }
     
                             break;
     
                         case 'quiz':
                             $quiz = Quiz::find($seq->id);
                             if($quiz){
                                 $quiz->deleted_at = date("Y-m-d H:i:s");
                                 $quiz->save();
                                 Quiz_Item::where("quiz_id", "=", $quiz->id)->update(["deleted_at"=>date("Y-m-d H:i:s")]);
                             }
                             break;
                    }
                 }
            }
            $section->save();
            $_arranged_sections = $this->rearrange_sections();
            return response()->json(["_arranged_sections"=>$_arranged_sections], 200);
        }
        return response()->json([], 422);

    }

    function rearrange_sections(){
        // re arrangement of sections
        $getting_sections = Section::where([
            "type"=>"course", "data_id"=> _current_course()->id,
            "deleted_at" => null,
        ])->get();

        if($getting_sections->count() > 0){
            // need to re arrange sections
            foreach ($getting_sections as $new_sequence_number => $old_section) {
                Section::where([
                    "section_number" => $old_section->section_number,
                    "type"=>"course", "data_id"=> _current_course()->id,
                    "deleted_at" => null
                ])->update(["section_number" => ($new_sequence_number + 1)]);
            }

            return true;
        }else{
            return false;
        }
    }

    function remove_part(Request $request) : JsonResponse
    {
        $section_number = $request->section;
        $part = $request->part;
        $type = $request->type;
        $id = $request->id;

        $section = Section::where([
            "type"=>"course", "data_id"=> _current_course()->id,
            "section_number" => $section_number,
            "deleted_at" => null,
        ])->first();

        if($section){
            if($section->sequences){
                $original_sequence = json_decode($section->sequences);
                $renew_sequence = [];
                $recurring_part = 0;
                foreach ($original_sequence as $original) {
                    
                    if($original->id == $id && $original->part == $part && $original->type == $type){
                    }else{
                        switch($original->type){
                            case "video":
                                $video = Video::find($original->id);
                                if($video){
                                    if($video->deleted_at==null && $video->id != $id){
                                        $recurring_part++;
                                        $renew_sequence[] = [
                                            "id" => $original->id,
                                            "part" => $recurring_part,
                                            "type" => $original->type,
                                        ];
                                    }else{
                                        if($video->cdn_url){
                                            $keypath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$video->filename}";
                                            $s3disk = Storage::disk('s3');
                                            $s3Bucket = $s3disk->getDriver()->getAdapter()->getBucket();
                                            $exists = $s3disk->exists($keypath);
                            
                                            if($exists){
                                               $s3disk->delete($keypath);
                                            }  
                                        }
                                    }
                                }
                            break;

                            case "article":
                                $article = Article::find($original->id);
                                if($article && $article->deleted_at==null){
                                    $recurring_part++;
                                    $renew_sequence[] = [
                                        "id" => $original->id,
                                        "part" => $recurring_part,
                                        "type" => $original->type,
                                    ];
                                }
                            break;

                            case "quiz":
                                $quiz = Quiz::find($original->id);
                                if($quiz && $quiz->deleted_at==null){
                                    $recurring_part++;
                                    $renew_sequence[] = [
                                        "id" => $original->id,
                                        "part" => $recurring_part,
                                        "type" => $original->type,
                                    ];
                                }
                            break;
                        }
                    }
                }

                $section->sequences = count($renew_sequence) > 0 ? json_encode($renew_sequence) : null; 
                if($section->save()){

                    switch ($type) {
                        case 'video':
                            $video = Video::find($id);
                            if($video){
                                $video->deleted_at = date("Y-m-d H:i:s");
                                $filename = $video->filename;
                                $video->save();
    
                                if($video->cdn_url){
                                    /**
                                     * remove video from s3 bucket
                                     * 
                                     */
                                    $s3 = Storage::disk('s3');
                                    $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$filename}";
                                    if($s3->delete($filepath)){
                                        return response()->json(["status"=>200, "message"=> "Video has been removed successfully!"], 200);
                                    }
                                }

                                return response()->json(["status"=>200, "message"=> "Video has been removed successfully!"], 200);
                                
                            }
                            break;
    
                        case 'article':
                            $article = Article::find($id);
                            if($article){
                                $article->deleted_at = date("Y-m-d H:i:s");
                                $article->save();
                            }

                            return response()->json(["status"=>200, "message"=> "Article has been removed successfully!"], 200);
                            break;
    
                        case 'quiz':
                            $quiz = Quiz::find($id);
                            if($quiz){
                                $quiz->deleted_at = date("Y-m-d H:i:s");
                                $quiz->save();
                            }

                            return response()->json(["status"=>200, "message"=> "Quiz has been removed successfully!"], 200);
                            break;
                    }
                }
                return response()->json(["status"=>500, "message"=> "Unable to remove {$type}. Please refresh your browser"], 200);
            }
            return response()->json(["status"=>200, "message"=> ucwords($type)." has been removed successfully!"], 200);
        }
        return response()->json(["status"=>500, "message"=> "Something went wrong! Please refresh your browser"], 200);
    }

    function delete_part($id, $type){
        switch ($type) {
            case 'video':
                $delete_video = Video::find($id);
                $delete_video->deleted_at = date("Y-m-d H:i:s");
                $filename = $delete_video->filename;
                $delete_video->save();

                /**
                 * remove video from s3 bucket
                 * 
                 */
                $s3 = Storage::disk('s3');
                $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/{$filename}";
                $s3->delete($filepath);

                break;

            case 'article':
                $delete_article = Article::find($id);
                $delete_article->deleted_at = date("Y-m-d H:i:s");
                $delete_article->save();

                break;

            case 'quiz':
                $delete_quiz = Quiz::find($id);
                $delete_quiz->deleted_at = date("Y-m-d H:i:s");
                $delete_quiz->save();

                $delete_quiz_item = Quiz_Item::where("quiz_id", $id);
                $delete_quiz_item->update([
                    "deleted_at" => date('Y-m-d H:i:s'),
                ]);

                break;
        }
    }

    function StoreVideoContentVideo(Request $request):JsonResponse {
        $id = _current_course()->id;
        $video_name = $request->video_name ?? "video_name" ;
        $video_cdn = $request->video ?? "corrupted";
        $filesize = $request->filesize ?? "0";
        $filename = $request->filename ?? "none";
        $length = $request->length ?? "0";
        $poster = $request->poster ?? "none";
        $thumbnail = $request->thumbnail ?? "none";
        $resolution = $request->resolution ?? "480p";
        $section_id = $request->section_id;
        $section_number = $request->section_number;
        $video_id = $request->video_id;
        $sequence_number = $request->sequence_number;
       
        if($id){
            if(Section::find($section_id)){
                    if($video_id){
                        $video = Video::find($video_id);
                        $video->section_id = $section_id;
                        $video->cdn_url = $video_cdn;
                        $video->filename = $video_name;
                        $video->size = $filesize;
                        $video->length = $length;
                        $video->poster = $poster;
                        $video->thumbnail = $thumbnail;
                        $video->resolution = json_encode($resolution);
                        $video->uploading_status = "done";
                        $video->created_at = date("Y-m-d H:i:s");
                        $video->updated_at = date("Y-m-d H:i:s");
                        $video->created_by = Auth::user()->id;
                        $video->sequence_number = $sequence_number;
                    }else{
                        $video = new Video;
                        $video->section_id = $section_id;
                        $video->cdn_url = $video_cdn;
                        $video->filename = $video_name;
                        $video->size = $filesize;
                        $video->length = $length;
                        $video->poster = $poster;
                        $video->thumbnail = $thumbnail;
                        $video->resolution = json_encode($resolution);
                        $video->uploading_status = "done";
                        $video->created_at = date("Y-m-d H:i:s");
                        $video->updated_at = date("Y-m-d H:i:s");
                        $video->created_by = Auth::user()->id;
                        $video->sequence_number = $sequence_number;
                    }

                   if($video->save()){
                    return response()->json(["status" => 200, "message" => "Video Inserted!", "video_id" => $video->id]);
                   }else{
                    return response()->json(["status" => 500, "message" => "Video Can't be Inserted!"]);
                   }
                   
                 
            }else{
                return response()->json(["status" => 500, "message" => "Section not Found!"]);
            }
           
        }else{
            return response()->json(["status" => 500, "message" => "Course not found!"]);
        }
    }

    function StoreVideoContentArticle(Request $request):JsonResponse {
   
        $id = _current_course()->id;
        $quiz_title = $request->article_title;
        $section_id = $request->section_id;
        $section_number = $request->section_number;
        $sequence_number = $request->sequence_number;
        $article_id= $request->article_id;
        $article_about = $request->about ?? "Something";
        $description = "Something Else";
        $image = "Something";
        if($id){
            if(Section::find($section_id)){
                if(! $article_id){
                    $article = new Article; 
                    
                    $article->section_id = $section_id;
                    $article->title = $article_title;
                    $article->sequence_number = $sequence_number;
                    $article->description = $description;
                    $article->image = $image;
                    $article->created_at =date("Y-m-d H:i:s");
                    $article->updated_at = date("Y-m-d H:i:s");
                    $article->created_by = Auth::user()->id;
                     
                    $article->save();
                }else{
                   
                    if($article_id){
                        $article = Article::find($article_id);
                        $article->title = $article_title;
                        $article->sequence_number =   $sequence_number;
                        $article->image = $image;
                        $article->description =  $article_about;
                        $article->updated_at = date("Y-m-d H:i:s");
                        
                        $article->save();
                    }else if($article_title){
                        $article = new Article; 
                    
                        $article->section_id = $section_id;
                        $article->title = $article_title;
                        $article->sequence_number = $sequence_number;
                        $article->description = $description;
                        $article->image = $image;
                        $article->created_at =date("Y-m-d H:i:s");
                        $article->updated_at = date("Y-m-d H:i:s");
                        $article->created_by = Auth::user()->id;
                         
                        $article->save();
                    }
                    else{
                        return response()->json(["status" => 500, "message" => "Article ID Not Fount!"]);
                    }
                }
                 
                  return response()->json(["status" => 200, "message" => "Article Inserted!", "article_title" => $article->title, "article_id" => $article->id]);
            }else{
                return response()->json(["status" => 500, "message" => "Section not Found!"]);
            }
           
        }else{
            return response()->json(["status" => 500, "message" => "Course not found!"]);
        }
    }

    function courseDetails(){
        $id = _current_course()->id;
        if($id){
            $course = Course::find($id);
            if($course->title && $course->profession_id && $course->headline && $course->description && $course->session_start && $course->session_end && $course->language){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function attratEnrollments(){
        $id = _current_course()->id;
      
        if($id){
            $course = Course::find($id);
          
            if($course->course_poster || $course->course_video || $course->objective || $course->requirements || $course->target_students ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function instructors(){
        $id = _current_course()->id;
      
        if($id){
            $course = Course::find($id);
          
            if($course->instructor_id ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function video_and_content(){
        $id = _current_course()->id;
      
        if($id){
            $sections = Section::where(["type"=>"course", "data_id"=>$id, "deleted_at"=>null])->get();
            if(! $sections->isEmpty()){
                foreach($sections as $key => $section){
                    $videos = Video::where("section_id",$section->id)->where("deleted_at",null)->first();
                    if($videos){
                        return true;
                    }
                }
            }
          
            return false;
          
        }else{
            return false;
        }
    }

    function handouts(){
        $id = _current_course()->id;
      
        if($id){
            $handouts = Handout::where([
                "type" => "course",
                "data_id" => $id,
                "deleted_at" => null
            ])->get();
        
            if(! $handouts->isEmpty()){
                
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function grading_assessment(){
        if(session("grading_assessment")){
            return true;
        }else{
            return false;
        }
    }

    function price_publish(){
        $id = _current_course()->id;
        if($id){
            $course = Course::find($id);

            if($course->program_accreditation_no && $course->approved_at && $course->total_unit_amounts && $course->price && $course->session_start && $course->session_end){
                return true;
            }else{
                return false;
            }
        }else{  
            return false;
        } 
    }

    function submit_for_accrediation(){
        $id = _current_course()->id;
        if($id){
            $course = Course::find($id);
            if($course->submit_accreditation_evaluation == 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function submitForReview() : JsonResponse
    {
        $error = [];
        
        if(!_course_creation_check("course_details")){ array_push($error,"Course Details"); }

        if(!_course_creation_check("attract_enrollments")){ array_push($error,"Attract Enrollments"); }

        if(!_course_creation_check("instructors")){ array_push($error,"Instructors"); }

        if(!_course_creation_check("video_content")){ array_push($error,"Video and Content"); }

        if(!_course_creation_check("handouts")){ array_push($error,"Handouts"); }

        if(!_course_creation_check("grading")){ array_push($error,"Grading & Assessment"); }

        if(!_course_creation_check("publish")){ array_push($error,"Publish Course"); }

        if(!_course_creation_check("submit_accreditation")){ array_push($error, "PRC Accreditation");  }

        if(count($error) > 0){
            return response()->json(["status" => 500, "message" => $error]);
        }

        $course = Course::find(_current_course()->id);
        $course->fast_cpd_status = "in-review";
        $user_provider= User::where("provider_id",$course->provider_id)->get();
        ///////////TEMPORARY 
        // if(date("Y-m-d",strtotime($course->session_start)) <= date("Y-m-d")){
        //     $course->fast_cpd_status = "live";
        // }else{
        //     $course->fast_cpd_status = "published";
        //     $course->published_at = date('Y-m-d H:i:s');
        // }
        ///////////TEMPORARY end

        _notification_insert(
            "course_creation",
            $user_provider[0]->id,
            _current_course()->id,
            "Course Submission for review",
            "We'll review ". $course->title . " soon. We'll notify you once we've approvided it.  ",
            "/provider/courses"

        );
        _send_notification_email($user_provider[0]->email,"course_for_review",_current_course()->id,$user_provider[0]->id);

        /////////////////superadmin
        foreach(_get_all_superadmin() as $superadmin){
            _notification_insert(
                "course_creation",
                $superadmin->id,
                _current_course()->id,
                "Course Submission for review",
                "New Course to Review ". $course->title,
                "/superadmin/verification/courses"
    
            );

            _send_notification_email($superadmin->email,"course_for_review_superadmin",_current_course()->id,$superadmin->id);
            
        }
        
        /////////////////end

        foreach(json_decode(_current_course()->instructor_id) as $inst){
            if(Auth::user()->id != $inst && $user_provider[0]->id != $inst){
                $info = User::find($inst);
                _notification_insert(
                    "course_creation",
                    $inst,
                    _current_course()->id,
                    "Course Submission for review",
                    _current_course()->title." is being reviewd. We'll notify you once we've approved the course.",
                    "/provider/courses"
                );
                _send_notification_email($info->email,"course_for_review",_current_course()->id,$inst);
            }
        }
        

        if($course->save()){
            return response()->json(["status" => 200, "message" => "Course has been successfully submitted for review!"]);
        }

        return response()->json(["status" => 500, "message" => "Unable to submit course for review! Please try again later"]);
    }

    function checkAccreditor(){
        $id = _current_course()->id;
      
        if($id){
            $course = Course::find($id);
            $username = "accreditor".date("Y").".".$course->provider_id.".".$course->id;
            $email = "accreditor".date("Y").".".$course->provider_id.".".$course->id."@fastcpd.com";
            $password =  bcrypt("Accreditor123");
            $users = User::where("email",$email)->first();
          
            if(! $users){
                $user = new User;
                $user->username = $username;
                $user->professions = json_encode("Accreditor");
                $user->name = $username;
                $user->email = $email;
                $user->password = $password;
                $user->status = "active";
                $user->accreditor = "active";
                $user->created_at = date("Y-m-d H:i:s");
                $user->created_by = 0;

                $user->save();
            }
            $data = (object)[
                "email" => $email,
                "password" => "Accreditor123",
            ];
            return $data;
        }else{
            return null;
        }
    }
    
    function DeleteVideoPart(Request $request): JsonResponse {
        $video_id = $request->video_id;
        if($video_id){
            $video = Video::find($video_id);
            $video->deleted_at = date("Y-m-d H:i:s");
            
            if($video->save()){
                return response()->json(["status"=>200,"message"=>"Video Deleted Succesfully!"]);
            }else{
                return response()->json(["status" => 500, "message" => "Error Occurred"]);
            }
        }else{
            return response()->json(["status" => 500, "message" => "Error Occurred"]);
        }
       
    }

    function DeleteArticlePart(Request $request): JsonResponse {
        $article_id = $request->article_id;
        if($article_id){
            $article = Article::find($article_id);
            $article->deleted_at = date("Y-m-d H:i:s");
            
            if($article->save()){
                return response()->json(["status"=>200,"message"=>"Article Deleted Succesfully!"]);
            }else{
                return response()->json(["status" => 500, "message" => "Error Occurred"]);
            }
        }else{
            return response()->json(["status" => 500, "message" => "Error Occurred"]);
        }
       
    }

    function get_sections($course) 
    {
        $sections =  Section::where([
            "type"=>"course", "data_id"=> $course,
            "deleted_at" => null,
        ])->get();

        $section_content_info = [];
        $section_content_rotation = [];
        $rotation = 0;
        $recurring_progress = 0;
        $video_count = 0;
        $article_count = 0;
        $quiz_count = 0;
        $hours_videos_count = 0;
        $parts_count = 0;
        foreach ($sections as $key => $section) {
            $detailed_sequence = [];
            $total_time = 0;

            if($section->sequences){
                $sequence = json_decode($section->sequences);

                foreach ($sequence as $key => $seq) {
                    
                    switch ($seq->type) {
                        case 'video':
                            $part_total_length = 0;
                            $video = Video::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $video_count = $video_count + 1;
                            if($video){ 
                                $time    = explode(':', $video->length);
                                if(count($time) == 3){
                                    $part_total_length += floatval($time[0]) * 60;
                                    $part_total_length += floatval($time[1]);
                                    $part_total_length += floatval($time[2]) /60;
                                    
                                }else{
                                    $part_total_length += floatval($time[0]);
                                    $part_total_length += floatval($time[1]) /100;

                                }
                                $hours_videos_count += $part_total_length;

                                $video_progress = Course_Progress::where([
                                    "course_id" => $section->data_id,
                                    "user_id" => Auth::user()->id,
                                    "section_id" => $section->id,
                                    "type" => "video",
                                    "data_id" => $video->id,
                                    "deleted_at" => null,
                                ])->first();
                                $video_data = [
                                    "section_id" => $section->id,
                                    "rotation" => $rotation,
                                    "id" => $video->id,
                                    "type" => "video",
                                    "sequence_number"=>$video->sequence_number,
                                    "title" => $video->title,
                                    "source" => $video->cdn_url,
                                    "filename" => $video->filename,
                                    "poster" => $video->poster,
                                    "thumbnail" => array(
                                        "0"=>[
                                            "src" => $video->poster,
                                            "style" => [
                                                "width"=>"200px",
                                                "left"=>"-55px"
                                            ]
                                        ]),
                                    "current_play_time" => $video_progress->played_time ?? 0,
                                    "video_length" => $part_total_length,
                                    "complete" => $video_progress ? ($video_progress->status=="completed" ? true : false) : false,
                                ];
                                $section_content_rotation[] = $video_data;
                                $detailed_sequence[] = $video_data;

                                $recurring_progress += ($video_progress ? ($video_progress->status=="completed" ? 1 : 0) : 0); 
                            }
                            break;

                        case 'article':

                            $article = Article::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $article_count = $article_count + 1;
                            if($article){
                                $total_time += floatval($article->reading_time);

                                $article_progress = Course_Progress::where([
                                    "course_id" => $section->data_id,
                                    "user_id" => Auth::user()->id,
                                    "section_id" => $section->id,
                                    "type" => "article",
                                    "data_id" => $article->id,
                                    "deleted_at" => null,
                                ])->first();
                                $article_data = [
                                    "section_id" => $section->id,
                                    "rotation" => $rotation,
                                    "id" => $article->id,
                                    "sequence_number" => $article->sequence_number,
                                    "type" => "article",
                                    "title" => $article->title,
                                    "body" => $article->description,
                                    "reading_time" => floatval($article->reading_time),
                                    "complete" =>  $article_progress ? ($article_progress->status=="completed" ? true : false) : false,
                                ];
                                $section_content_rotation[] = $article_data;
                                $detailed_sequence[] = $article_data;

                                $recurring_progress += ($article_progress ? ($article_progress->status=="completed" ? 1 : 0) : 0);
                            }
                            break;

                        case 'quiz':

                            $quiz = Quiz::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $quiz_count = $quiz_count + 1;
                            if($quiz){ 
                                $quiz_items = Quiz_Item::select("id", "question", "choices", "answer")->where([
                                    "quiz_id" => $quiz->id,
                                    "deleted_at" => null,
                                ])->get()->toArray();

                                if(count($quiz_items) > 0 ){
                                    $quiz_progress = Course_Progress::where([
                                        "course_id" => $section->data_id,
                                        "user_id" => Auth::user()->id,
                                        "section_id" => $section->id,
                                        "type" => "quiz",
                                        "data_id" => $quiz->id,
                                        "deleted_at" => null,
                                    ])->first();
                                    $quiz_data = [
                                        "section_id" => $section->id,
                                        "rotation" => $rotation,
                                        "id" => $quiz->id,
                                        "type" => "quiz",
                                        "sequence_number"=>$quiz->sequence_number,
                                        "title" => $quiz->title,
                                        "reading_time" => 0,
                                        "items" => $quiz_items,
                                        "complete" => $quiz_progress ? ($quiz_progress->status == "passed" ? true : false) : false,
                                        "status" => $quiz_progress->status ?? "none", // in-progress, completed, failed, passed
                                        "overall" => $quiz_progress->quiz_overall ?? [],
                                    ];
                                    $section_content_rotation[] = $quiz_data;
                                    $detailed_sequence[] = $quiz_data;

                                    $recurring_progress += ($quiz_progress ? ($quiz_progress->status=="passed" ? 1 : 0) : 0);
                                }
                            }
                            break;
                    }

                    $rotation++;
                }
                $parts_count += count($detailed_sequence);
                $section_content_info[] = [
                    "id" => $section->id,
                    "title" => $section->name,
                    "number"=> $section->section_number,
                    "section_number"=> $section->sequence_number,
                    "objective"=>$section->objective,
                    "total_time" =>  number_format($total_time, 2, '.', ','),
                    "parts" => $detailed_sequence,
                    "complete" => false,
                ];
            }
        }
        return $data = array(
            "section" => $section_content_info,
            "video_count" => $video_count,
            "article_count" => $article_count,
            "quiz_count" => $quiz_count,
            "total_video_length" => $hours_videos_count,
            "parts_count"=> $parts_count
        );
    }

    function StoreExpenseBreakdown(Request $request) : JsonResponse{

        $expenses_breakdown = array(
            "venue_details" => $request->venue_details,
            "venue_cost" => $request->venue_cost,
            "meal_details" => $request->meal_details,
            "meal_cost" => $request->meal_cost,
            "honoraria_details" => $request->honoraria_details,
            "honoraria_cost" => $request->honoraria_cost,
            "item_materials_details" => $request->item_materials_details,
            "item_materials_cost" => $request->item_materials_cost,
            "advertising_expenses_details" => $request->advertising_expenses_details,
            "advertising_expenses_cost" => $request->advertising_expenses_cost,
            "transportation_details" => $request->transportation_details,
            "transportation_cost" => $request->transportation_cost,
            "accommodation_details" => $request->accommodation_details,
            "accommodation_cost" => $request->accommodation_cost,
            "process_fee_details" => $request->process_fee_details,     
            "process_fee_cost" => $request->process_fee_cost,
            "supplies_equip_details" => $request->supplies_equip_details, 
            "supplies_equip_cost" => $request->supplies_equip_cost, 
            "laboratory_details" => $request->laboratory_details, 
            "laboratory_cost" => $request->laboratory_cost,               
            "vat_details" => $request->vat_details, 
            "vat_cost" => $request->vat_cost, 
            "entrance_fee_details" => $request->entrance_fee_details, 
            "entrance_fee_cost" => $request->entrance_fee_cost, 
            "facilitator_fee_details" => $request->facilitator_fee_details, 
            "facilitator_fee_cost" => $request->facilitator_fee_cost, 
            "misc_details" => $request->misc_details, 
            "misc_cost" => $request->misc_cost,
            "total_breakdown" => $request->total_breakdown,
        );
        $response = Course::where("id",_current_course()->id)->update([
            "expenses_breakdown" => json_encode($expenses_breakdown)
        ]);
        return response()->json(["status" => 200]);
    }


    function cpdas(Request $request)
    {
        $course = Course::select("courses.profession_id", "courses.instructor_id", "providers.name", "providers.accreditation_number", "courses.session_end"
        ,"courses.session_start","courses.session_end","courses.title","courses.description","courses.objectives","courses.target_students"
        ,"courses.price","courses.submit_accreditation_evaluation","courses.provider_id","courses.pass_percentage","courses.id","courses.target_number_students"
        ,"courses.expenses_breakdown","courses.course_poster","courses.submit_accreditation_evaluation")
                        ->where("courses.id", $request->id)
                        ->leftJoin("providers","providers.id","courses.provider_id")
                        ->first();
        $user = User::where("provider_id",$course->provider_id)->first();
        $section_data = $this->get_sections($request->id);
        $handouts = Handout::where([
            "type" => "course",
            "data_id" => $request->id,
            "deleted_at" => null,                                                                                                                  
        ])->get();
        $instructor_info= User::whereIn("id", json_decode($course->instructor_id))->get();
        $instructor_resume= Instructor_Resume::whereIn("user_id", json_decode($course->instructor_id))->get();
        $prc_logo= "https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/prc_logo.png";
        $instructional_design = Instructional_Design::where([
            "type" => "course",
            "data_id" => $request->id,
        ])->get();
        $headers = ["#", "Name", "Position", "Role", "Contact", "Email", "Verified", "Status"];
        $data_part1 = array(
            "name_provider" => $course->name,
            "accreditation_no" => $course->accreditation_number,
            "expire_date" => $course->session_end,
            "contact_person" => $user->name,
            "designation" => "Owner",
            "contact_number" => $user->contact,
            "email_add" => $user->email,
            "signature" => $user->signature,
            "date_of_application" => $course->session_start,
            "title_of_program" => $course->title,
            "date_offered" => $course->session_start ." to ".$course->session_end,
            "start_date" =>  date("M d, Y", strtotime($course->session_start)),
            "end_date" => date("M d, Y", strtotime($course->session_end)),
            "duration" => "1 Year",
            "time" => _course_total_time_length($course->id),
            "place_venue" => "https://www.fastcpd.com",
            "times_program" => "Online",
            "course_description" => _strip_design_text($course->description),
            "objectives" => json_decode($course->objectives),
            "target_perticipants" => json_decode($course->target_students),
            "registration_fee" => $course->price,
            "course_profession_id" => _get_profession(json_decode($course->profession_id)[0])->profession,
            "target_number_students" => $course->target_number_students,
            "course_poster"=> $course->course_poster,
            "accreditors_email"=> json_decode($course->submit_accreditation_evaluation)->email,
            "accreditors_pass"=> json_decode($course->submit_accreditation_evaluation)->password,
        );
        // dd($data_part1);
        $accreditor_account = json_decode($course->submit_accreditation_evaluation);
        $support_docs_data = array(
            "course_id" =>  $course->id,
            "accreditor_email" => $accreditor_account->email,
            "accreditor_pass" => $accreditor_account->password,
            "expire_date" => $course->session_end,
            "sections" => $section_data['section'],
            "video_count" => $section_data['video_count'],
            "article_count" => $section_data['article_count'],
            "quiz_count" => $section_data['quiz_count'],
            "parts_count" => $section_data['parts_count'],
            "total_video_length" => $section_data['total_video_length'],
            "handout_count" => count($handouts),
            "pass_percentage" => $course->pass_percentage,
            "instructors" => $instructor_info,
            "instructor_resumes"=> $instructor_resume,
        );
        $records = [];
        $mpdf = new \Mpdf\Mpdf([
            "format" => 'Legal',
            "falseBoldWeight" => 9,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 5,
            'margin_bottom' => 0,
            'margin_header' => 0,
            // "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
        ]);



        $data = array(
            "course_info" => $data_part1,
            "support_docs_data" => $support_docs_data,
            "prc_logo" => $prc_logo,
            "instructional_design" => $instructional_design,
            "expenses" => json_decode($course->expenses_breakdown)
        ); 
        // _send_notification_email("juncahilig08@gmail.com",'payout_completed','10','7');
        return view('/template/relative/cpdas' ,$data);
        // _send_notification_email("m.cahilig.ipp@gmail.com","instructor_course_invitation",30,3);f


    }

    function disclaimer_session(Request $request) : JsonResponse{

        $data_array = [];
        if(Session::has("reminded_courses")){
            $session_array = Session::get("reminded_courses");
            array_push($session_array, _current_course()->id);
            Session::put("reminded_courses", $session_array);
        }else{
            Session::push("reminded_courses", _current_course()->id);
        }
        Session::save();
        return response()->json(["status" => 200]);
    }
    function disclaimer(Request $request) : JsonResponse{

        if(Session::has("reminded_courses")){
            if(array_search(_current_course()->id,Session::get("reminded_courses")) === false){
                return response()->json(["status" => false]);
            }else{
                return response()->json(["status" => true]);
            }
        }else{
            return response()->json(["status" => false]);
        }
        return response()->json(["status" => 200]);
        
    }

}

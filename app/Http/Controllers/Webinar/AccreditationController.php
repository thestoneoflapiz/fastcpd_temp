<?php

namespace App\Http\Controllers\Webinar;

use App\Http\Controllers\Controller;
use App\{
User, Provider, Co_Provider, Instructor, 
    Profession, Instructor_Resume,
    Webinar, Webinar_Instructor_Permission as WIP,
    Instructional_Design,
    Section, Video, Article, Quiz, Quiz_Item,
};

use Illuminate\Support\Facades\{Storage,Auth,DB};
use Illuminate\Http\{Request, JsonResponse};

use Response;
use Session;
use URL;

class AccreditationController extends Controller
{
    function index(Request $request){
        $instructor = User::whereIn("id", _current_webinar()->instructor_id ? json_decode(_current_webinar()->instructor_id) : [])->get();
        $instructional_design = Instructional_Design::where([
            "type" => "webinar",
            "data_id" => _current_webinar()->id,
            "deleted_at" => null
        ])->get();
        $schedule_details = _schedule_details_perDay(_current_webinar()->id,_current_webinar()->event);
        $data = [
            "accreditor_account" => $this->check_accreditor(),
            "learning_outcomes" => $this->get_sections(_current_webinar()->id),
            "objectives" => json_decode(_current_webinar()->objectives),
            "webinar_instructors" => $instructor,
            "webinar_instructor_ids" => [],
            "instructional_design" => $instructional_design,
            "schedule_details" => $schedule_details,
            "with_price" => ($prices = _current_webinar()->prices) ? json_decode($prices)->with : 0,
            
        ];

        if(_my_webinar_permission("accreditation")){
            return view('page/webinar/management/accreditation', $data);
        }
        
        return view('template/errors/404');  
    }

    function check_accreditor(){
        $id = _current_webinar()->id;
        if($id){
            $webinar = Webinar::find($id);
            $username = "accreditor".date("Y").".".$webinar->provider_id.".".$webinar->id;
            $email = "accreditor".date("Y").".".$webinar->provider_id.".".$webinar->id."@fastcpd.com";
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

    function get_sections($webinar) 
    {
        $sections =  Section::where([
            "type"=>"webinar", "data_id"=> $webinar,
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
                            $video = Video::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $video_count = $video_count + 1;
                            if($video){ 
                                $hours_videos_count += floatval(str_replace(":", ".", $video->length));
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
                                    "video_length" => floatval(str_replace(":", ".", $video->length)),
                                ];
                                $section_content_rotation[] = $video_data;
                                $detailed_sequence[] = $video_data;
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
                                $article_data = [
                                    "section_id" => $section->id,
                                    "rotation" => $rotation,
                                    "id" => $article->id,
                                    "sequence_number" => $article->sequence_number,
                                    "type" => "article",
                                    "title" => $article->title,
                                    "body" => $article->description,
                                    "reading_time" => floatval($article->reading_time),
                                ];
                                $section_content_rotation[] = $article_data;
                                $detailed_sequence[] = $article_data;

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
                                    $quiz_data = [
                                        "section_id" => $section->id,
                                        "rotation" => $rotation,
                                        "id" => $quiz->id,
                                        "type" => "quiz",
                                        "sequence_number"=>$quiz->sequence_number,
                                        "title" => $quiz->title,
                                        "reading_time" => 0,
                                        "items" => $quiz_items,
                                    ];
                                    $section_content_rotation[] = $quiz_data;
                                    $detailed_sequence[] = $quiz_data;
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

    function can_submit_accreditation(Request $request) : JsonResponse 
    {
        $errors = [];
        if(!_webinar_creation_check('webinar_details') ){
           $errors[] = "Webinar Details" ;
        }

        if(!_webinar_creation_check('attract_enrollments')){
           $errors[] = "Attract Enrollments" ;
        }

        if(!_webinar_creation_check('instructors')){
            $errors[] = "Instructors" ;
        }

        if(!_webinar_creation_check('video_content')){
            $errors[] = "Video & Content" ;
        }

        if(!_webinar_creation_check('grading')){
            $errors[] = "Grading Assessments" ;
        }

        if(!_webinar_creation_check('handouts')){
            $errors[] = "Handouts" ;
        }

        $webinar = Webinar::find(_current_webinar()->id);
        if($webinar && $webinar->instructor_id!=null){
            $instructors = json_decode($webinar->instructor_id);
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

    function store_accreditation(Request $request) : JsonResponse{
        $id = _current_webinar()->id;
        if($id){
            $price = $request->price ?? 0;
            $evaluation = (int)$request->evaluation;

            $response = Webinar::find($id);
            if($response->prices){
                $prices = json_decode($response->prices);
                $prices->with = $request->price;
                $response->prices = json_encode($prices);
            }else{
                $response->prices = json_encode([
                    "with" => $request->price,
                    "without" => 0,
                ]);
            }
            $response->target_number_students = $request->target_number_students;

            if($response->save()){
                return response()->json(["status" => 200 , "message" => "Price and Target Students successfully saved!"]);
            }else{
                return response()->json(["status" => 500 , "message" => "Something went wrong! Please refresh your browser"]);
            }
        }else{
            return response()->json(["status" => 500, "message" => "Webinar not found! Please try again later" ]);
        }
    }

    function store_instructional(Request $request) : JsonResponse{
        $id = _current_webinar()->id;
        $find = Instructional_Design::where([
            "type" => "webinar",
            "data_id" => _current_webinar()->id,
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
                $find->type = "webinar";
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
            "webinar_creation",
            Auth::user()->id,
            _current_webinar()->id,
            "Webinar Submission for review",
            "You're almost done. Submit your "._current_webinar()->title."'s link and share the email & password for evaluators",
            "/provider/webinars"

        );
        foreach(json_decode(_current_webinar()->instructor_id) as $inst){
            if(Auth::user()->id != $inst){
                _notification_insert(
                    "webinar_creation",
                    $inst,
                    _current_webinar()->id,
                    "Webinar Submission for review",
                    _current_webinar()->title." will now be submitted for accreditation.",
                    "/provider/webinars"
                );
            }
            
        }

        

        $webinar = Webinar::find($id);

        foreach(json_decode($webinar->profession_id) as $prof_id){
            $accreditation[] = array(
                'id'=> $prof_id,
                'units'=> "",
                'program_no'=> "",
                
            );
        }
        $webinar->accreditation = json_encode($accreditation);
        $webinar->prc_status = "approved";
        $webinar->approved_at = date("Y-m-d H:i:s");
        if($webinar->submit_accreditation_evaluation==null){
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
                'name' => "Accreditor for "._current_webinar()->title,
                'email' => $acc_email,
                'accreditor' => 'active',
                'password' => bcrypt($acc_password),
                'status' => 'active',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]); 
    
            $webinar->submit_accreditation_evaluation = json_encode([
                "evaluation" => true,
                "email" => $acc_email,
                "password" => $acc_password
            ]);
            $app_url = \config('app.link') . "/data/pdf/user/webinar_certificate/" . $id;
            $webinar->certificate_templates = json_encode([
                \config('app.link') . "/data/pdf/user/webinar_certificate/" . $id
            ]);
        }else{
            $current_evaluation = json_decode($webinar->submit_accreditation_evaluation);
            $current_evaluation->evaluation = true;

            $webinar->submit_accreditation_evaluation = json_encode($current_evaluation);
        }
        $webinar->save();



        return response()->json(["status" => 200, "id" => $id]);
    }

    function store_expense_breakdown(Request $request) : JsonResponse{

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
        $response = Webinar::where("id",_current_webinar()->id)->update([
            "expenses_breakdown" => json_encode($expenses_breakdown)
        ]);
        return response()->json(["status" => 200]);
    }

    function view_cpdas(Request $request)
    {
        $webinar = Webinar::select("webinars.profession_id", "webinars.instructor_id", "providers.name", "providers.accreditation_number","webinars.title","webinars.description","webinars.objectives","webinars.target_students"
        ,"webinars.prices","webinars.submit_accreditation_evaluation","webinars.provider_id","webinars.pass_percentage","webinars.id","webinars.target_number_students","webinars.event","webinars.webinar_poster"
        ,"webinars.expenses_breakdown","webinars.webinar_poster","webinars.submit_accreditation_evaluation")
                        ->where("webinars.id", $request->id)
                        ->leftJoin("providers","providers.id","webinars.provider_id")
                        ->first();
        $user = User::where("provider_id",$webinar->provider_id)->first();
        $schedule_details = _schedule_details_perDay($request->id,$webinar->event);
        $section_data = $this->get_sections($request->id);
        $instructor_info= User::whereIn("id", json_decode($webinar->instructor_id))->get();
        $instructor_resume= Instructor_Resume::whereIn("user_id", json_decode($webinar->instructor_id))->get();
        $prc_logo= "https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/prc_logo.png";
        $instructional_design = Instructional_Design::where([
            "type" => "webinar",
            "data_id" => $request->id,
        ])->get();
        $headers = ["#", "Name", "Position", "Role", "Contact", "Email", "Verified", "Status"];
        $data_part1 = array(
            "name_provider" => $webinar->name,
            "accreditation_no" => $webinar->accreditation_number,
            "expire_date" => $webinar->session_end,
            "contact_person" => $user->name,
            "designation" => "Owner",
            "contact_number" => $user->contact,
            "email_add" => $user->email,
            "signature" => $user->signature,
            "date_of_application" => $webinar->session_start,
            "title_of_program" => $webinar->title,
            "date_offered" => $webinar->session_start ." to ".$webinar->session_end,
            "start_date" =>  $schedule_details['start_date'],
            "end_date" => $schedule_details['end_date'],
            "duration" => "1 Year",
            "time" => $schedule_details['hours'],
            "place_venue" => "Zoom",
            "times_program" => "Online",
            "course_description" => _strip_design_text($webinar->description),
            "objectives" => json_decode($webinar->objectives),
            "target_perticipants" => json_decode($webinar->target_students),
            "registration_fee" => $webinar->price,
            "course_profession_id" => _get_profession(json_decode($webinar->profession_id)[0])->profession,
            "target_number_students" => $webinar->target_number_students,
            "course_poster"=> $webinar->webinar_poster,
            "accreditors_email"=> json_decode($webinar->submit_accreditation_evaluation)->email,
            "accreditors_pass"=> json_decode($webinar->submit_accreditation_evaluation)->password,
        );
        // dd($data_part1);
        $accreditor_account = json_decode($webinar->submit_accreditation_evaluation);
        $support_docs_data = array(
            "webinar_id" =>  $webinar->id,
            "accreditor_email" => $accreditor_account->email,
            "accreditor_pass" => $accreditor_account->password,
            "expire_date" => $webinar->session_end,
            "sections" => $section_data['section'],
            "video_count" => $section_data['video_count'],
            "article_count" => $section_data['article_count'],
            "quiz_count" => $section_data['quiz_count'],
            "parts_count" => $section_data['parts_count'],
            "total_video_length" => $section_data['total_video_length'],
            "pass_percentage" => $webinar->pass_percentage,
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
            "expenses" => json_decode($webinar->expenses_breakdown)
        ); 
        // _send_notification_email("juncahilig08@gmail.com",'payout_completed','10','7');
        return view('/template/relative/cpdas' ,$data);
        // _send_notification_email("m.cahilig.ipp@gmail.com","instructor_webinar_invitation",30,3);f


    }
}
<?php

namespace App\Http\Controllers\Course;

use App\{User, Profession, Provider, Co_Provider, Instructor, 
    Course, Section, Video, Article, Quiz, Quiz_Item, Quiz_Score,
    Course_Progress, Handout,
    Purchase, Purchase_Item,Certificate
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Support\Collection;

use Response; 
use Session; 

class LiveController extends Controller
{
    function live(Request $request)
    {
        if(!Auth::user()->first_name && !Auth::user()->last_name){

            Session::flash("info", "Due to changes, please complete your FULL NAME before viewing the live course. Thank you!");
            return redirect("/profile/personal");
        }

        $handouts = null;
        $course = Course::with("provider")->where(["url"=>$request->url])->first();
        if($course){

            /**
             * check if purchased
             * 
             */
            if(_can_view_live("course", $course->id)){
                if($course->prc_status == "approved" && in_array($course->fast_cpd_status, ["live", "ended"])){
                    if($course->allow_handouts==1){
                        $handouts = Handout::where([
                            "type" => "course",
                            "data_id" => $course->id,
                            "deleted_at" => null
                        ])->get();
                    }
                    
                    if($course->instructor_id!=null){
                        $instructors = Instructor::with("profile")->whereIn("id", json_decode($course->instructor_id))->where([
                            "provider_id" => $course->provider_id,
                        ])->get();
                    }else{
                        $instructors = [];
                    }
        
                    $img_handout = [
                        "pdf" => "https://www.fastcpd.com/img/pdf.png",
                        "xls" => "https://www.fastcpd.com/img/excel.png",
                        "csv" => "https://www.fastcpd.com/img/excel.png",
                        "zip" => "https://www.fastcpd.com/img/folder.png",
                        "other" => "https://www.fastcpd.com/img/document.png"
                    ];
                    $certificate = Certificate::where([
                        "user_id" => Auth::user()->id,
                        "type" => "course",
                        "data_id" => $course->id
                    ])->first();
                    
                    $data = [
                        "course" => $course,
                        "instructors" => $instructors,
                        "progress" => null,
                        "handouts" => $handouts,
                        "handout_img" => $img_handout,
                        "rating" => null,
                        "total" => array (
                            "video_hours" => _course_total_video_length($course->id),
                            "quizzes" => _course_total_quizzes($course->id),
                            "handouts" => $handouts ? $handouts->count() : 0,
                        ),
                        "certificates" => $certificate
                    ];
                    
                    return view('page/live-course/live', $data);
                }
            }

            return view('template/errors/course');
        }

        return view('template/errors/404');
    }

    function preview(Request $request)
    {
        $handouts = null;
        $course = Course::with("provider")->where(["url"=>$request->url])->first();
        if($course){
            if(_course_creation_check("course_details") && _course_creation_check("preview_live")){
                if($course->allow_handouts==1){
                    $handouts = Handout::where([
                        "type" => "course",
                        "data_id" => $course->id,
                        "deleted_at" => null
                    ])->get();
                }
    
                if($course->instructor_id!=null){
                    $instructors = Instructor::with("profile")->whereIn("id", json_decode($course->instructor_id))->where([
                        "provider_id" => _current_provider()->id,
                    ])->get();
                }else{
                    $instructors = [];
                }
    
                $img_handout = [
                    "pdf" => "https://www.fastcpd.com/img/pdf.png",
                    "xls" => "https://www.fastcpd.com/img/excel.png",
                    "csv" => "https://www.fastcpd.com/img/excel.png",
                    "zip" => "https://www.fastcpd.com/img/folder.png",
                    "other" => "https://www.fastcpd.com/img/document.png"
                ];
    
                $data = [
                    "course" => $course,
                    "instructors" => $instructors,
                    "progress" => null,
                    "handouts" => $handouts,
                    "handout_img" => $img_handout,
                    "certificates" => null,
                    "rating" => null,
                    "total" => array (
                        "video_hours" => _course_total_video_length($course->id),
                        "quizzes" => _course_total_quizzes($course->id),
                        "handouts" => $handouts ? $handouts->count() : 0,
                    )
                ];
                
                if(_is_co_provider(_current_provider()->id) || _is_provider_instructor(Auth::user()->id, _current_provider()->id)){
                    // please add another validation here if the user is enrolled to this course!!
                    return view('page/live-course/live', $data);
                }
            }
        }

        return view('template/errors/404');
    }

    function get_sections(Request $request) : JsonResponse
    {
        $course = $request->course_id;
        $sections =  Section::where([
            "type"=>"course", "data_id"=> $course,
            "deleted_at" => null,
        ])->get();

        $section_content_info = [];
        $section_content_rotation = [];
        $rotation = 0;
        $recurring_progress = 0;
        foreach ($sections as $key => $section) {
            $section_progress = 0;
            $detailed_sequence = [];
            $total_time = 0;

            $accu_hours = 0;
            $accu_minutes = 0;
            $accu_seconds = 0;

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
                            
                            if($video){ 
                                if($video->length){
                                    $exploded_video_time = explode(":", $video->length);
                                    if(count($exploded_video_time) == 3){
                                        $accu_hours += $exploded_video_time[0];
                                        $accu_minutes += $exploded_video_time[1];
                                        $accu_seconds += $exploded_video_time[2];
                                    }else{
                                        $accu_minutes += $exploded_video_time[0];
                                        $accu_seconds += $exploded_video_time[1];
                                    }
                                }

                                $video_progress = Course_Progress::where([
                                    "course_id" => $section->data_id,
                                    "section_id" => $section->id,
                                    "user_id" => Auth::user()->id,
                                    "type" => "video",
                                    "data_id" => $video->id,
                                    "deleted_at" => null,
                                ])->first();

                                $video_data = [
                                    "section_id" => $section->id,
                                    "rotation" => $rotation,
                                    "id" => $video->id,
                                    "type" => "video",
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
                                    "video_length" => $video->length,
                                    "complete" =>  $video_progress && $video_progress->status ? ($video_progress->status=="completed" ? true : false) : false,
                                ];

                                if($video_progress){
                                    if($video_progress->status=="completed"){
                                        $section_progress+=1;
                                    }
                                }

                                $section_content_rotation[] = $video_data;
                                $detailed_sequence[] = $video_data;

                                $recurring_progress += ($video_progress && $video_progress->status ? ($video_progress->status=="completed" ? 1 : 0) : 0);
                                $rotation++;
                            }
                            break;
    
                        case 'article':
    
                            $article = Article::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
                            if($article){
                                if($article->reading_time){
                                    $exploded_article_time = explode(".", $article->reading_time);
                                    if(count($exploded_article_time) == 3){
                                        $accu_hours += $exploded_article_time[0];
                                        $accu_minutes += $exploded_article_time[1];
                                        $accu_seconds += $exploded_article_time[2];
                                    }else{
                                        $accu_minutes += $exploded_article_time[0];
                                        $accu_seconds += $exploded_article_time[1];
                                    }
                                }

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
                                    "type" => "article",
                                    "title" => $article->title,
                                    "body" => $article->description,
                                    "reading_time" => str_replace(".",":",$article->reading_time),
                                    "complete" =>  $article_progress ? ($article_progress->status=="completed" ? true : false) : false,
                                ];

                                if($article_progress){
                                    if($article_progress->status=="completed"){
                                        $section_progress+=1;
                                    }
                                }

                                $section_content_rotation[] = $article_data;
                                $detailed_sequence[] = $article_data;

                                $recurring_progress += ($article_progress ? ($article_progress->status=="completed" ? 1 : 0) : 0);
                                $rotation++;
                            }
                            break;
    
                        case 'quiz':
    
                            $quiz = Quiz::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
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
                                        "title" => $quiz->title,
                                        "reading_time" => 0,
                                        "items" => $quiz_items,
                                        "complete" => $quiz_progress ? ($quiz_progress->status == "completed" ? true : false) : false,
                                        "status" => $quiz_progress->status ?? "none", // in-progress, completed
                                        "overall" => $quiz_progress->quiz_overall ?? [],
                                    ];

                                    if($quiz_progress){
                                        if($quiz_progress->status=="completed"){
                                            $section_progress+=1;
                                        }
                                    }

                                    $section_content_rotation[] = $quiz_data;
                                    $detailed_sequence[] = $quiz_data;

                                    $recurring_progress += ($quiz_progress ? ($quiz_progress->status=="completed" ? 1 : 0) : 0);
                                }else{
                                    $quiz_data = [
                                        "section_id" => $section->id,
                                        "rotation" => $rotation,
                                        "id" => $quiz->id,
                                        "type" => "quiz",
                                        "title" => $quiz->title,
                                        "reading_time" => 0,
                                        "items" => null,
                                        "complete" => false,
                                        "status" => "none", // in-progress, completed
                                        "overall" => [],
                                    ];
                                    
                                    $section_content_rotation[] = $quiz_data;
                                    $detailed_sequence[] = $quiz_data;
                                }

                                $rotation++;
                            }
                            break;
                    }
                }

                $quotient = ($accu_seconds >= 60) ? floor($accu_seconds / 60) : 0;
                $total_minutes = $quotient+$accu_minutes;
                $divided_minutes = $total_minutes / 60;
                $dm_whole = floor($divided_minutes);
                $dm_decimal = $divided_minutes - $dm_whole;
    
                $divided_seconds = ($accu_seconds >= 60) ? ($accu_seconds / 60) : $accu_seconds;
                $ds_whole = floor($divided_seconds);
                $ds_decimal = $divided_seconds - $ds_whole;
                $ds_decimal = number_format($ds_decimal, 1, ".", "");
                $ds_explode = explode(".", $ds_decimal);
                $seconds = end($ds_explode);

                $minutes = $dm_decimal * 60;
                $hours = $dm_whole + $accu_hours;
    
                $hours = $hours<10&&$hours>=0 ? sprintf("%02d", $hours) : $hours;
                $minutes = $minutes<10&&$minutes>=0 ? sprintf("%02d", $minutes) : $minutes;
                $seconds = $seconds<10&&$seconds>=0 ? sprintf("%02d", $seconds) : $seconds;

                $section_content_info[] = [
                    "id" => $section->id,
                    "title" => $section->name,
                    "total_time" => "$hours:$minutes:$seconds",
                    "detailed_sequence" => $detailed_sequence,
                    "complete" => false,
                    "current_progress" => $section_progress,
                ];
            }
        }

        $progress_overall = [
            "current" => $recurring_progress,
            "total" => count($section_content_rotation),
        ];

        return response()->json(["data"=> [
            "section_content_info" => $section_content_info,
            "section_content_rotation" => $section_content_rotation,
        ], "progress" => $progress_overall], 200);
    }

    function progress_save(Request $request) : JsonResponse
    {
        $course_id = $request->course_id;
        $preview = $request->preview_;
        $type = $request->type;
        $data = $request->data_;

        $section = $data["section"] ?? $data["section_id"];
        if($preview == "preview"){

            /**
             * 
             * progress true will add +1 on the progress trophy and etc.
             */
            return response()->json(["progress"=>true]);
        }else{
            $progress = Course_Progress::where([
                "user_id" => Auth::user()->id,
                "course_id" => $course_id,
                "section_id" => $section,
                "type" => $type,
                "data_id" => $data["id"],
            ])->first();
            
            switch ($type) {
                case 'video':
                    if($progress){
                        $progress->played_time = $data['current_play_time'] ?? 0;
                        $progress->status = $data['complete'] == "true" ? "completed" : "in-progress";
                        $progress->updated_at = date("Y-m-d H:i:s");
                        $progress->save();
                    }else{
                        Course_Progress::insert([
                            "user_id" => Auth::user()->id,
                            "course_id" => $course_id,
                            "section_id" => $section,
                            "type" => $type,
                            "data_id" => $data["id"],
                            "played_time" => $data['current_play_time'] ?? 0,
                            "status" => $data['complete'] == "true" ? "completed" : "in-progress",
                            "created_at" => date("Y-m-d H:i:s"),
                            "updated_at" => date("Y-m-d H:i:s"),
                        ]);
                    }

                    if(_get_live_course_progress($course_id)==100){
                        Purchase_Item::where([
                            "user_id" => Auth::user()->id,
                            "type" => "course",
                            "data_id" => $course_id,
                            "payment_status" => "paid",
                            "fast_status" => "incomplete"
                        ])->update([
                            "fast_status" => "complete"
                        ]);
                    }
                    return response()->json([
                        "section_progress" => $this->get_section_progress($section),
                    ]);
                    break;

                case 'article':
                    Course_Progress::insert([
                        "user_id" => Auth::user()->id,
                        "course_id" => $course_id,
                        "section_id" => $section,
                        "type" => $type,
                        "data_id" => $data["id"],
                        "status" => "completed",
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s"),
                    ]);

                    if(_get_live_course_progress($course_id)==100){
                        Purchase_Item::where([
                            "user_id" => Auth::user()->id,
                            "type" => "course",
                            "data_id" => $course_id,
                            "payment_status" => "paid",
                            "fast_status" => "incomplete"
                        ])->update([
                            "fast_status" => "complete"
                        ]);
                    }

                    return response()->json([
                        "section_progress" => $this->get_section_progress($section),
                    ]);
                    break;

                case 'quiz':
                    if($progress){
                        $progress->quiz_overall = json_encode($data["overall"]);
                        $progress->status = $data['status'];
                        $progress->updated_at = date("Y-m-d H:i:s");
                        $progress->save();
                    }else{
                        Course_Progress::insert([
                            "user_id" => Auth::user()->id,
                            "course_id" => $course_id,
                            "section_id" => $section,
                            "type" => $type,
                            "data_id" => $data["id"],
                            "status" => $data["status"],
                            "quiz_overall" => json_encode($data["overall"]),
                            "created_at" => date("Y-m-d H:i:s"),
                            "updated_at" => date("Y-m-d H:i:s"),
                        ]);
                    }

                    if(_get_live_course_progress($course_id)==100){
                        Purchase_Item::where([
                            "user_id" => Auth::user()->id,
                            "type" => "course",
                            "data_id" => $course_id,
                            "payment_status" => "paid",
                            "fast_status" => "incomplete"
                        ])->update([
                            "fast_status" => "complete"
                        ]);
                    }

                    return response()->json([
                        "section_progress" => $this->get_section_progress($section),
                    ]);
                    break;
            }
        }

        return response()->json([], 422);
    }

    function check_live(Request $request) : JsonResponse
    {
        if(_course_creation_check("course_details") && _course_creation_check("preview_live")){
            return response()->json([]);
        }else{
            $error = [];
            if(!_course_creation_check("course_details")){
                $error[] = ["Course Details: All fields are required to be filled up"];
            }

            if(!_course_creation_check("preview_live")){
                $error[] = ["At least one(1) of <b>Video & Content's Section</b> should have at least one(1) part"];
                $error[] = ["<b>Attract Enrollements</b>: Only the <b>Course Video</b> is not required"];
            }

            return response()->json(["error"=>$error], 422);
        }
    }

    function get_section_progress($section_id)
    {
        $section_progress = 0;
        $section =  Section::find($section_id);
        if($section && $section->sequences){
            $sequence = json_decode($section->sequences);

            foreach ($sequence as $key => $seq) {
                switch ($seq->type) {
                    case 'video':
                        $video_progress = Course_Progress::where([
                            "course_id" => $section->data_id,
                            "user_id" => Auth::user()->id,
                            "section_id" => $section->id,
                            "type" => "video",
                            "data_id" => $seq->id,
                            "deleted_at" => null,
                        ])->first();

                        if($video_progress){
                            if($video_progress->status=="completed"){
                                $section_progress+=1;
                            }
                        }
                        break;

                    case 'article':

                        $article_progress = Course_Progress::where([
                            "course_id" => $section->data_id,
                            "user_id" => Auth::user()->id,
                            "section_id" => $section->id,
                            "type" => "article",
                            "data_id" => $seq->id,
                            "deleted_at" => null,
                        ])->first();

                        if($article_progress){
                            if($article_progress->status=="completed"){
                                $section_progress+=1;
                            }
                        }
                        break;

                    case 'quiz':

                        $quiz_progress = Course_Progress::where([
                            "course_id" => $section->data_id,
                            "user_id" => Auth::user()->id,
                            "section_id" => $section->id,
                            "type" => "quiz",
                            "data_id" => $seq->id,
                            "deleted_at" => null,
                        ])->first();
                        
                        if($quiz_progress){
                            if($quiz_progress->status=="completed"){
                                $section_progress+=1;
                            }
                        }
                        break;
                }
            }
        }


        return $section_progress;
    }

    function get_grade_requirements(Request $request) : JsonResponse
    {
        $data = Course::select("id", "title", "assessment", "allow_retry")->find($request->course_id);
        if($data){
            $data->assessment = json_decode($data->assessment);
        }
        return response()->json($data, 200);
    }

    function get_overall_quiz_grade(Request $request) : JsonResponse
    {
        $data = [];
        $quiz_ids = [];
        $passing_percentage = $request->assessment["percentage"] * 100;

        if($request->preview_=="live"){
            $sections = Section::select("sequences")
            ->where(["type"=>"course", "data_id"=>$request->course_id])
            ->where("deleted_at", "=",  null)->get();

            if($sections && $sections->count() > 0){
                foreach ($sections as $key => $sc) {
                    $sequences = $sc->sequences ? json_decode($sc->sequences) : [];
                    foreach ($sequences as $seq) {
                        switch ($seq->type) {
                            case 'quiz':
                                $quiz_ids[] = $seq->id;
                                break;
                        }
                    }
                }

                $items = Quiz_Item::select("id")->whereIn("quiz_id", $quiz_ids)
                ->where("deleted_at", "=", null)->get();

                if($items && $items->count() > 0){
                    $progress = Course_Progress::select("quiz_overall")->where([
                        "course_id" => $request->course_id,
                        "user_id" => Auth::user()->id,
                        "type" => "quiz"
                    ])->where("deleted_at", "=", null)->get();

                    if($progress && $progress->count() > 0){
                        $total_correct = 0;
                        $total_items = $items->count();

                        foreach ($progress as $prg) {
                            $overALL = $prg->quiz_overall ? json_decode($prg->quiz_overall) : [];
                            if($overALL){
                                $total_correct += $overALL->total;
                            }
                        }
                        if($total_correct > 0){
                            $correct_percentage = ($total_correct / $items->count()) * 100;
                            $data = [
                                "total" => $total_correct,
                                "items" => $items->count(),
                                "correct_percentage" => $correct_percentage,
                                "passing_percentage" => $passing_percentage,
                            ];


                            if($correct_percentage >= $passing_percentage){
                                $data["status"] = "passed";
                            }else{
                                $data["status"] = "incomplete";
                            }
                        }else{
                            $data = [
                                "status" => "incomplete",
                                "total" => 0,
                                "items" => $items->count(),
                                "correct_percentage" => 0,
                                "passing_percentage" => $passing_percentage,
                            ];
                        }
                    }else{
                        $data = [
                            "total" => 0,
                            "items" => $items->count(),
                            "correct_percentage" => 0,
                            "passing_percentage" => $passing_percentage,
                            "status" => "incomplete"
                        ];
                    }
                }
            }
        }
        
        return response()->json($data, 200);
    }
}

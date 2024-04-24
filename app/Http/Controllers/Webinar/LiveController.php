<?php

namespace App\Http\Controllers\Webinar;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, 
    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Instructor_Permission as WIP,
    Webinar_Progress, Webinar_Attendance,
    Webinar_Rating, Webinar_Performance,
    Section, Video, Article, Quiz, Quiz_Item,
    Handout,
    Certificate,
    Purchase_Item,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class LiveController extends Controller
{
    function index(Request $request)
    {
        if(!Auth::user()->first_name && !Auth::user()->last_name){
            Session::flash("info", "Due to changes, please complete your FULL NAME before viewing the live webinar. Thank you!");
            return redirect("/profile/personal");
        }

        $handouts = null;
        $webinar = Webinar::with("provider")->where(["url"=>$request->url, "type"=>"official"])->first();
        if($webinar){
            $session = _webinar_session($webinar);
            if($session==null){
                return view('template/errors/course', ["type" => "webinar"]);
            }

            /**
             * check if purchased
             * 
             */
            if(_can_view_live("webinar", $webinar->id)){
                if($webinar->fast_cpd_status == "live"){
                    if($webinar->allow_handouts==1){
                        $handouts = Handout::where([
                            "type" => "webinar",
                            "data_id" => $webinar->id,
                            "deleted_at" => null
                        ])->get();
                    }
                    
                    if($webinar->instructor_id!=null){
                        $instructors = Instructor::with("profile")->whereIn("id", json_decode($webinar->instructor_id))->where([
                            "provider_id" => $webinar->provider_id,
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
                        "type" => "webinar",
                        "data_id" => $webinar->id
                    ])->first();

                    $attendance = Webinar_Attendance::where([
                        "webinar_id" => $webinar->id,
                        "session_id" => $session->id,
                        "user_id" => Auth::user()->id,
                    ])->first();
                    
                    $data = [
                        "webinar" => $webinar,
                        "session" => $session,
                        "attendance" => $attendance,
                        "instructors" => $instructors,
                        "progress" => null,
                        "handouts" => $handouts,
                        "handout_img" => $img_handout,
                        "rating" => null,
                        "total" => array (
                            "quizzes" => _webinar_total_quizzes($webinar->id),
                            "handouts" => $handouts ? $handouts->count() : 0,
                        ),
                        "certificates" => $certificate
                    ];

                    return view('page/webinar/live', $data);
                }
                return view('template/errors/course', ["type" => "webinar"]);
            }
        }

        return view('template/errors/404');
    }

    function get_sections(Request $request) : JsonResponse
    {
        $webinar = Webinar::select("id", "event")->find($request->webinar_id);
        if($webinar){
            switch ($webinar->event) {
                case 'day':
                    $sections = Section::where([
                        "type" => "webinar",
                        "data_id" => $webinar->id,
                        "deleted_at" => null,
                    ])->get();
                    
                    return response()->json($this->webinar_section($sections));
                break;

                case 'series':
                    $schedule = _webinar_schedule($webinar->id, $webinar->event);
                    $current = _webinar_session($webinar);
                    $session_section_order = 0;
                    foreach($schedule as $sched){
                        if($sched["sessions"]){
                            foreach($sched["sessions"] as $key => $sess){
                                if($sess["id"] == $current->id){
                                    $session_section_order = $key+1;
                                    break;
                                }
                            }
                        }
                    }   

                    $sections = Section::where([
                        "type" => "webinar",
                        "data_id" => $webinar->id,
                        "section_number" => $session_section_order,
                        "deleted_at" => null,
                    ])->get();
                    
                    return response()->json($this->webinar_section($sections));
                break;
            }
        }
    }

    function webinar_section($sections){
        $section_content_info = [];
        $section_content_rotation = [];
        $rotation = 0;
        $recurring_progress = 0;

        foreach ($sections as $key => $section) {
            $section_progress = 0;
            $detailed_sequence = [];
            if($section->sequences){
                $sequence = json_decode($section->sequences);

                foreach ($sequence as $key => $seq) {
                    switch ($seq->type) {
                        case 'article':
                            $article = Article::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
                            if($article){
                                $article_progress = Webinar_Progress::where([
                                    "webinar_id" => $section->data_id,
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
                                    $quiz_progress = Webinar_Progress::where([
                                        "webinar_id" => $section->data_id,
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

                $section_content_info[] = [
                    "id" => $section->id,
                    "title" => date("F d, ")." Outline",
                    "detailed_sequence" => $detailed_sequence,
                    "complete" => false,
                    "current_progress" => $section_progress,
                ];
            }
        }

        return [
            "progress" => [
                "current" => $recurring_progress,
                "total" => count($section_content_rotation),
            ],
            "data" => [
                "section_content_info" => $section_content_info,
                "section_content_rotation" => $section_content_rotation,
            ]
        ];
    }

    function progress_save(Request $request) : JsonResponse
    {
        $webinar_id = $request->webinar_id;
        $preview = $request->preview_;
        $type = $request->type;
        $data = $request->data_;

        $webinar = Webinar::find($webinar_id);

        $section = $data["section"] ?? $data["section_id"];
        if($preview == "preview"){

            /**
             * 
             * progress true will add +1 on the progress trophy and etc.
             */
            return response()->json(["progress"=>true]);
        }else{
            $progress = Webinar_Progress::where([
                "user_id" => Auth::user()->id,
                "webinar_id" => $webinar_id,
                "section_id" => $section,
                "type" => $type,
                "data_id" => $data["id"],
            ])->first();
            
            switch ($type) {
                case 'article':
                    Webinar_Progress::insert([
                        "user_id" => Auth::user()->id,
                        "webinar_id" => $webinar_id,
                        "section_id" => $section,
                        "type" => $type,
                        "data_id" => $data["id"],
                        "status" => "completed",
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s"),
                    ]);

                    if(_get_live_webinar_progress($webinar)==100){
                        Purchase_Item::where([
                            "user_id" => Auth::user()->id,
                            "type" => "webinar",
                            "data_id" => $webinar_id,
                            "payment_status" => "paid",
                            "fast_status" => "incomplete",
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
                        Webinar_Progress::insert([
                            "user_id" => Auth::user()->id,
                            "webinar_id" => $webinar_id,
                            "section_id" => $section,
                            "type" => $type,
                            "data_id" => $data["id"],
                            "status" => $data["status"],
                            "quiz_overall" => json_encode($data["overall"]),
                            "created_at" => date("Y-m-d H:i:s"),
                            "updated_at" => date("Y-m-d H:i:s"),
                        ]);
                    }

                    if(_get_live_webinar_progress($webinar)==100){
                        Purchase_Item::where([
                            "user_id" => Auth::user()->id,
                            "type" => "webinar",
                            "data_id" => $webinar_id,
                            "payment_status" => "paid",
                            "fast_status" => "incomplete",
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
        if(_webinar_creation_check("webinar_details") && _webinar_creation_check("preview_live")){
            return response()->json([]);
        }else{
            $error = [];
            if(!_webinar_creation_check("webinar_details")){
                $error[] = ["Webinar Details: All fields are required to be filled up"];
            }

            if(!_webinar_creation_check("preview_live")){
                $error[] = ["At least one(1) of <b>Video & Content's Section</b> should have at least one(1) part"];
                $error[] = ["<b>Attract Enrollements</b>: Only the <b>Webinar Video</b> is not required"];
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
                    case 'article':

                        $article_progress = Webinar_Progress::where([
                            "webinar_id" => $section->data_id,
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

                        $quiz_progress = Webinar_Progress::where([
                            "webinar_id" => $section->data_id,
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
        $data = Webinar::select("id", "title", "assessment", "allow_retry")->find($request->webinar_id);
        if($data){
            $data->assessment = json_decode($data->assessment);
        }
        return response()->json($data, 200);
    }

    function get_overall_quiz_grade(Request $request) : JsonResponse
    {
        $data = [];
        $passing_percentage = $request->assessment["percentage"] * 100;

        if($request->preview_=="live"){
            $sections = Section::select("sequences")
            ->where(["type"=>"webinar", "data_id"=>$request->webinar_id])
            ->where("deleted_at", "=",  null)->get();

            if($sections && $sections->count() > 0){
                $quiz_ids = [];
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
                    $progress = Webinar_Progress::select("quiz_overall")->where([
                        "webinar_id" => $request->webinar_id,
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

    function attendance_save(Request $request) : JsonResponse
    {
        if(Auth::check()){
            if($request->type=="in"){
                if($request->info["attendance"]){
                    if($request->info["attendance"]["session_in"]){
                        return response()->json([]);
                    }

                    $attendance = Webinar_Attendance::find($request->info["attendance"]["id"]);
                    $attendance->session_in = date("Y-m-d H:i:s");
                    $attendance->updated_at = date("Y-m-d H:i:s");
                    $attendance->save();
                }else{
                    $attendance = new Webinar_Attendance;
                    $attendance->webinar_id = $request->info["webinar"]["id"];
                    $attendance->session_id = $request->info["session"]["id"];
                    $attendance->user_id = Auth::user()->id;
                    $attendance->session_in = date("Y-m-d H:i:s");
                    $attendance->created_at = date("Y-m-d H:i:s");
                    $attendance->updated_at = date("Y-m-d H:i:s");
                    $attendance->save();
                }

                return response()->json([
                    "data" => Webinar_Attendance::find($attendance->id)
                ]);
            }else{
                if($request->info["attendance"]){
                    if($request->info["attendance"]["session_out"]){
                        return response()->json([]);
                    }

                    $attendance = Webinar_Attendance::find($request->info["attendance"]["id"]);
                    $attendance->session_out = date("Y-m-d H:i:s");
                    $attendance->updated_at = date("Y-m-d H:i:s");
                    $attendance->save();
                }else{
                    $attendance = new Webinar_Attendance;
                    $attendance->webinar_id = $request->info["webinar"]["id"];
                    $attendance->session_out = $request->info["session"]["id"];
                    $attendance->user_id = Auth::user()->id;
                    $attendance->session_in = date("Y-m-d H:i:s");
                    $attendance->created_at = date("Y-m-d H:i:s");
                    $attendance->updated_at = date("Y-m-d H:i:s");
                    $attendance->save();
                }
                return response()->json([
                    "data" => Webinar_Attendance::find($attendance->id)
                ]);
            }
        }
        return response()->json([], 422);
    }
}
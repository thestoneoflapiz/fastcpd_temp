<?php

namespace App\Http\Controllers\Webinar;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, Handout,
    Purchase, Purchase_Item,
    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Progress, Webinar_Rating, Webinar_Performance,
    Webinar_Attendance,
    Webinar_Instructor_Permission as WIP,
    Section, Video, Article, Quiz,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class PerformanceController extends Controller
{
    function index(Request $request){
        $webinar = _current_webinar();
        if($webinar && in_array($webinar->fast_cpd_status, ["published", "live", "ended"])){

            $schedule = Webinar_Session::select("session_date")->where([
                "webinar_id" => $webinar->id,
                "deleted_at" => null,
            ])->orderBy("session_date")->get();

            $handouts = null;
            if($webinar->allow_handouts==1){
                $handouts = Handout::where([
                    "type" => "webinar",
                    "data_id" => $webinar->id,
                    "deleted_at" => null
                ])->get();
            }

            $data = [
                "webinar" => $webinar,
                "schedule" => $schedule,
                "handouts" => $handouts,
            ];

            return view('page/webinar/portal/performance/index', $data);
        }

        return view('template/errors/404');
    }

    function reports(Request $request) : JsonResponse
    {
        $webinar = _current_webinar();
        $timeline = collect();
        $attendees_in = collect();
        $attendees_out = collect();
        $registered = collect(); // when webinar purchase is done

        /**
         * Timeline
         */
        $progress = Webinar_Progress::select("user_id", "created_at", "updated_at", "data_id", "type", "quiz_overall", "status")->where([
            "webinar_id" => $webinar->id,
            "deleted_at" => null,
        ])->whereDate("created_at", "=", $request->_selected_date)->get();

        foreach ($progress as $prog) {
            $user = User::select("id","first_name")->find($prog->user_id);

            switch ($prog->type) {
                case 'quiz':
                    $quiz = Quiz::select("title")->find($prog->data_id);
                    if($prog->status == "completed"){
                        $overall = json_decode($prog->quiz_overall);
                    
                        $timeline[] = [
                            "user_id" => $user ? $user->id : 0,
                            "user_name" => $user ? $user->first_name : "Someone",
                            "type" => $prog->type,
                            "text" => "started taking \"{$quiz->title}\" quiz",
                            "time_" => date("h:i A", strtotime($prog->updated_at)),
                        ];

                        $timeline[] = [
                            "user_id" => $user ? $user->id : 0,
                            "user_name" => $user ? $user->first_name : "Someone",
                            "type" => $prog->type,
                            "text" => "completed \"{$quiz->title}\" quiz with a {$overall->total}/{$overall->items}",
                            "time_" => date("h:i A", strtotime($prog->updated_at)),
                        ];
                    }else{
                        $timeline[] = [
                            "user_id" => $user ? $user->id : 0,
                            "user_name" => $user ? $user->first_name : "Someone",
                            "type" => $prog->type,
                            "text" => "started taking \"{$quiz->title}\" quiz",
                            "time_" => date("h:i A", strtotime($prog->updated_at)),
                        ];
                    }
                    break;

                case 'article':
                    $article = Article::select("title")->find($prog->data_id);
                    if($prog->status == "completed"){
                        $overall = json_decode($prog->article_overall);
                    
                        $timeline[] = [
                            "user_id" => $user ? $user->id : 0,
                            "user_name" => $user ? $user->first_name : "Someone",
                            "type" => $prog->type,
                            "text" => "started taking \"{$article->title}\" article",
                            "time_" => date("h:i A", strtotime($prog->updated_at)),
                        ];

                        $timeline[] = [
                            "user_id" => $user ? $user->id : 0,
                            "user_name" => $user ? $user->first_name : "Someone",
                            "type" => $prog->type,
                            "text" => "completed \"{$article->title}\" article",
                            "time_" => date("h:i A", strtotime($prog->updated_at)),
                        ];
                    }else{
                        $timeline[] = [
                            "user_id" => $user ? $user->id : 0,
                            "user_name" => $user ? $user->first_name : "Someone",
                            "type" => $prog->type,
                            "text" => "started taking \"{$article->title}\" article",
                            "time_" => date("h:i A", strtotime($prog->updated_at)),
                        ]; 
                    }
                    break;
            }
        }

        $timeline_attendance = Webinar_Attendance::select("user_id", "session_in", "session_out")->where([
            "webinar_id" => $webinar->id,
            "deleted_at" => null,
        ])->whereDate("created_at", "=", $request->_selected_date)->get();

        foreach ($timeline_attendance as $att) {
            $user = User::select("id","first_name")->find($att->user_id);
            
            if($att->session_in){
                $timeline[] = [
                    "user_id" => $user ? $user->id : 0,
                    "user_name" => $user ? $user->first_name : "Someone",
                    "type" => "in",
                    "text" => "attended for IN",
                    "time_" => date("h:i A", strtotime($att->session_in)),
                ];
            }

            if($att->session_out){
                $timeline[] = [
                    "user_id" => $user ? $user->id : 0,
                    "user_name" => $user ? $user->first_name : "Someone",
                    "type" => "out",
                    "text" => "attended for OUT",
                    "time_" => date("h:i A", strtotime($att->session_out)),
                ];
            }
        }

        $ratings = Webinar_Rating::select("user_id", "created_at", "rating")->where([
            "webinar_id" => $webinar->id,
        ])->whereDate("created_at", "=", $request->_selected_date)->get();

        foreach ($ratings as $rt) {
            $user = User::select("id","first_name")->find($rt->user_id);
            
            $timeline[] = [
                "user_id" => $user ? $user->id : 0,
                "user_name" => $user ? $user->first_name : "Someone",
                "type" => "rating",
                "text" => "rated \"{$webinar->title}\" a {$rt->rating} stars",
                "time_" => date("h:i A", strtotime($rt->created_at)),
            ];
        }

        $performance = Webinar_Performance::select("user_id", "created_at")->where([
            "webinar_id" => $webinar->id,
        ])->whereDate("created_at", "=", $request->_selected_date)->get();

        foreach ($performance as $perf) {
            $user = User::select("id","first_name")->find($perf->user_id);
            
            $timeline[] = [
                "user_id" => $user ? $user->id : 0,
                "user_name" => $user ? $user->first_name : "Someone",
                "type" => "performance",
                "text" => "rated \"{$webinar->title}\" performance",
                "time_" => date("h:i A", strtotime($perf->created_at)),
            ];
        }

        /**
         * Attendance
         */
        $attendance = Webinar_Attendance::select("user_id", "session_in", "session_out")->where([
            "webinar_id" => $webinar->id,
            "deleted_at" => null,
        ])->whereDate("created_at", "=", $request->_selected_date)->get();

        foreach ($attendance as $att) {
            $user = User::select("id","first_name", "username", "name")->find($att->user_id);
            
            if($att->session_in){
                $attendees_in[] = [
                    "user_id" => $user ? $user->id : 0,
                    "user_first" => $user ? $user->first_name : "Someone",
                    "user_name" => $user ? $user->name : "Someone",
                    "user_url" => $user ? "/user/{$user->username}" : null,
                    "type" => "in",
                    "time_" => date("h:i A", strtotime($att->session_in)),
                ];
            }

            if($att->session_out){
                $attendees_out[] = [
                    "user_id" => $user ? $user->id : 0,
                    "user_first" => $user ? $user->first_name : "Someone",
                    "user_name" => $user ? $user->name : "Someone",
                    "user_url" => $user ? "/user/{$user->username}" : null,
                    "type" => "out",
                    "time_" => date("h:i A", strtotime($att->session_out)),
                ];
                $attendees_out->sortBy("time_")->values();
            }
        }
        
        $attendees = [
            "in" => $attendees_in->sortBy("time_")->values(),
            "out" => $attendees_out->sortBy("time_")->values(),
        ];

        $schedule_id = null;
        if($webinar->event=="day"){
            $session = Webinar_Session::select("id")->whereDate("session_date", "=", $request->_selected_date)
                ->where("webinar_id", "=", $webinar->id)->first();
            if($session){
                $schedule_id = $session->id;
            }
        }else{
            $session = Webinar_Session::select("id")->whereDate("session_date", "=", $request->_selected_date)
                ->where("webinar_id", "=", $webinar->id)->first();
            if($session){
                $series = Webinar_Series::select("id")->whereRaw("JSON_CONTAINS(sessions, '{$session->id}')")->first();
                if($series){
                    $schedule_id = $series->id;
                }
            }
        }

        if($schedule_id){
            $purchases = Purchase_Item::select("id", "purchase_id", "user_id")->where([
                "type" => "webinar",
                "data_id" => $webinar->id,
                "schedule_type" => $webinar->event,
                "schedule_id" => $schedule_id,
                "payment_status" => "paid",
            ])->get();


            $total_items = 0;
            $sections = Section::select("id", "sequences")->where([
                "type" => "webinar",
                "data_id" => $webinar->id,
                "deleted_at" => null,
            ])->get();

            if($sections){
                foreach ($sections as $sec) {
                    if($sec->sequences){
                        $sequences = json_decode($sec->sequences);
                        foreach ($sequences as $sq) {
                            if($sq->type!="video"){
                                $total_items++;
                            }
                        }
                    }
                }
            }

            foreach ($purchases as $purchase) {
                $pr = Purchase::select("payment_at")->find($purchase->purchase_id);
                $user = User::select("id","first_name", "username", "name")->find($purchase->user_id);

                $registered[] = [
                    "user_id" => $user ? $user->id : 0,
                    "user_first" => $user ? $user->first_name : "Someone",
                    "user_name" => $user ? $user->name : "Someone",
                    "user_url" => $user ? "/user/{$user->username}" : null,
                    "type" => "registered",
                    "time_" => date("h:i A", strtotime($pr->payment_at)),
                    "attendance" => $this->user_attendance($webinar->id, $user ? $user->id : 0),
                    "rating" => $this->user_rating($webinar->id, $user ? $user->id : 0),
                    "progress" => $this->user_progress($webinar->id, $user ? $user->id : 0, $total_items),
                ];
            }
        }

        return response()->json([
            "attendees" => $attendees,
            "registered" => $registered->sortBy("time_")->values(),
            "timeline" => $timeline->sortBy("time_")->values(),
        ]);
    }

    function manual_attendance(Request $request) : JsonResponse
    {
        $webinar = _current_webinar();
        $session = Webinar_Session::whereDate("session_date", "=", $request->_selected_date)
            ->where([
                "webinar_id" => $webinar->id,
            ])->first();

        if($session){

            $find = Webinar_Attendance::where([
                "webinar_id" => $webinar->id,
                "session_id" => $session->id,
                "user_id" => $request->select_registered_user,
                "deleted_at" => null,
            ])->first();

            if($request->attendance_for=="in"){
                if($find){
                    if(date("Y-m-d", strtotime($find->session_in)) == $request->_selected_date){
                        return response()->json([
                            "message" => "User already attended <b>IN</b>!"
                        ], 422);
                    }

                    $find->session_in = "{$request->_selected_date} ".date("H:i:s", strtotime($request->attendance_time));
                    $find->updated_at = date("Y-m-d H:i:s");
                    $find->save();
                }else{

                    Webinar_Attendance::insert([
                        "webinar_id" => $webinar->id,
                        "session_id" => $session->id,
                        "user_id" => $request->select_registered_user,
                        "session_in" => "{$request->_selected_date} ".date("H:i:s", strtotime($request->attendance_time)),
                        "created_at" => "{$request->_selected_date} ".date("H:i:s", strtotime($request->attendance_time)),
                        "created_by" => Auth::user()->id,
                        "updated_at" => date("Y-m-d H:i:s"),
                    ]);
                }

                return response()->json([]);
            }else{
                if($find){
                    if(date("Y-m-d", strtotime($find->session_out)) == $request->_selected_date){
                        return response()->json([
                            "message" => "User already attended <b>OUT</b>!"
                        ], 422);
                    }

                    $find->session_out = "{$request->_selected_date} ".date("H:i:s", strtotime($request->attendance_time));
                    $find->updated_at = date("Y-m-d H:i:s");
                    $find->save();
                }else{
                    return response()->json([
                        "message" => "User must attend for <b>IN</b> first!"
                    ], 422);
                }

                return response()->json([]);
            }
        }

        return response()->json([], 422);
    }

    function user_progress($webinar_id, $user_id, $items)
    {
        $progress = Webinar_Progress::select("id")->where([
            "user_id" => $user_id,
            "webinar_id" => $webinar_id,
            "status" => "completed"
        ])->get();

        if($progress && $progress->count() > 0){
            if($progress->count()>=$items){
                return true;

            }
        }

        return false;
    }

    function user_rating($webinar_id, $user_id){
        $rating = Webinar_Rating::select("id")->where([
            "webinar_id" => $webinar_id,
            "user_id" => $user_id,
        ])->first();

        $performance = Webinar_Performance::select("id")->where([
            "webinar_id" => $webinar_id,
            "user_id" => $user_id,
        ])->first();

        if($rating && $performance){
            return true;
        }

        return false;
    }

    function user_attendance($webinar_id, $user_id){
        $attendance = Webinar_Attendance::where([
            "webinar_id" => $webinar_id,
            "user_id" => $user_id,
            "deleted_at" => null,
        ])->first();

        return $attendance;
    }
}
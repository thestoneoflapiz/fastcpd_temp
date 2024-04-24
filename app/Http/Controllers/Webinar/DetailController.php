<?php

namespace App\Http\Controllers\Webinar;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, 
    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Instructor_Permission as WIP,
    Section, Video, Article, Quiz,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class DetailController extends Controller
{
    function index(Request $request){
        if(_my_webinar_permission("webinar_details") && _webinar_creation_restricted("webinar_details")){
            $format = null;
            if(_current_webinar()->event=="day"){
                $schedule = Webinar_Session::where("webinar_id", "=", _current_webinar()->id)->get();
                if($schedule->count() > 0){
                    $format = array_map(function($sched){
                        return [
                            "id" => str_replace("-", "", $sched["session_date"]),
                            "date_" => $sched["session_date"],
                            "sessions" => json_decode($sched["sessions"]),
                        ];
                    }, $schedule->toArray());
                }

                $data = [
                    "schedule" => $schedule->count() > 0 ? true : false,
                    "format" => $format,
                ];

            }else{
                $series = Webinar_Series::where("webinar_id", "=", _current_webinar()->id)->get();
                if($series->count() > 0){
                    foreach ($series as $srs) {
                        $json_sched = json_decode($srs->sessions);
                        $schedule = Webinar_Session::where("webinar_id", "=", _current_webinar()->id)
                            ->whereIn("id", $json_sched)->get();
                        if($schedule->count() > 0){
                            foreach ($schedule as $key => $sched) {
                                $format[] = [
                                    "series" => $srs->series_order,
                                    "id" => str_replace("-", "", $sched->session_date),
                                    "date_" => $sched->session_date,
                                    "sessions" => json_decode($sched->sessions),
                                ];
                            }
                        }
                    }

                    $format = collect($format);
                }

                $data = [
                    "schedule" => $format ? true : false,
                    "format" => $format ? $format->sortBy("series") : null,
                ];
            }

            return view('page/webinar/management/details', $data);
        }

        return view('template/errors/404');  
    }

    function save_webinar_info(Request $request) : JsonResponse
    {
        $webinar = Webinar::find(_current_webinar()->id);
        $webinar->title = $request->title;
        $webinar->headline = $request->headline;
        $webinar->description = $request->about;
        $webinar->language = $request->language;
        if($webinar->save()){
            return response()->json([]);
        }

        return response()->json([], 422);
    }

    function save_webinar_schedule(Request $request) : JsonResponse
    {
        $schedule = collect($request->schedule);
        $series = [];
        $database = collect([]);

        if(_current_webinar()->event == "day"){
            $time_session = null;
            foreach($schedule as $index => $sched){
                if($index==0){
                    $time_session = $sched["sessions"];
                }
                $database[] = [
                    "webinar_id" => _current_webinar()->id,
                    "session_date" => date("Y-m-d", strtotime($sched["date_"])),
                    "sessions" => json_encode($time_session),
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ];
            }

            $database = $database->sortBy("session_date")->toArray();
        }else{
            $schedule = $schedule->sortBy("series");
            $first_series_sessions = [];

            $current_series_index = 1;
            $current_session_index = 0;
            foreach($schedule as $sched){
                if($sched["series"] == 1){
                    $series[$sched["series"]][] = [
                        "webinar_id" => _current_webinar()->id,
                        "session_date" => date("Y-m-d", strtotime($sched["date_"])),
                        "sessions" => json_encode($sched["sessions"]),
                    ];
                    $first_series_sessions[] = json_encode($sched["sessions"]);
                }else{
                    if($current_series_index==$sched["series"]){
                        $series[$sched["series"]][] = [
                            "webinar_id" => _current_webinar()->id,
                            "session_date" => date("Y-m-d", strtotime($sched["date_"])),
                            "sessions" => $first_series_sessions[$current_session_index],
                        ];
                        $current_session_index++;
                    }else{
                        $current_series_index = $sched["series"];
                        $current_session_index=0;
                        $series[$sched["series"]][] = [
                            "webinar_id" => _current_webinar()->id,
                            "session_date" => date("Y-m-d", strtotime($sched["date_"])),
                            "sessions" => $first_series_sessions[$current_session_index],
                        ];
                        $current_session_index++;
                    }
                }
            }

            for ($i=0; $i < $request->series_count; $i++) { 
                $current_series = collect($series[$i+1]);
                $database[] = $current_series->sortBy("session_date");
            }

        }
        
        $sections = Section::where([
            "type" => "webinar",
            "data_id" => _current_webinar()->id,
            "deleted_at" => null
        ])->get();

        if(_current_webinar()->event == "day"){
            if($sections->count() == 0){
                Section::insert([
                    "type" => "webinar",
                    "data_id" => _current_webinar()->id,
                    "section_number" => 1,
                    "name" => "Day [selected] Outline",
                    "objective" => "Students will learn a valueable lesson",
                    "created_by" => Auth::user()->id,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
            }
            
            Webinar_Session::where("webinar_id", "=", _current_webinar()->id)->delete();
            Webinar_Session::insert($database);
        }else{
            if($sections->count() > 0){
                foreach($sections as $sec){
                    if($sec->sequences!=null){
                        return response()->json([
                            "message" => "We can't let you add/remove schedules, Sections on your Video & Content have existing data."
                        ], 422);
                    }
                }

                /**
                 * make adjustments
                 * 
                 */
                Section::where([
                    "type" => "webinar",
                    "data_id" => _current_webinar()->id
                ])->delete();
                $this->create_webinar_series($database, $series, true);
            }else{
                $this->create_webinar_series($database, $series, true);
            }
        }

        return response()->json([]);
    }

    function create_webinar_series($database, $series, $recreate_sections){
        Webinar_Session::where("webinar_id", "=", _current_webinar()->id)->delete();

        $webinar_series_database = [];
        foreach ($database as $key => $db_series) {
            $session_ids = [];

            foreach ($db_series as $sched) {
                $new_session = new Webinar_Session;
                $new_session->webinar_id = _current_webinar()->id;
                $new_session->session_date = $sched["session_date"];
                $new_session->sessions = $sched["sessions"];
                $new_session->created_at = date("Y-m-d H:i:s");
                $new_session->updated_at = date("Y-m-d H:i:s");
                $new_session->save();

                $session_ids[] = $new_session->id;
            }

            $webinar_series_database[] = [
                "webinar_id" => _current_webinar()->id,
                "series_order" => $key+1,
                "sessions" => json_encode($session_ids),
                "created_by" => Auth::user()->id,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ];
        }

        if($recreate_sections){
            $this->create_webinar_sections($series);
        }
        
        Webinar_Series::where("webinar_id", "=", _current_webinar()->id)->delete();
        Webinar_Series::insert($webinar_series_database);
    }

    function create_webinar_sections($series){
        $sections = [];

        $series_section_name = [];
        foreach($series as $index_series => $ss){
            foreach ($ss as $index_day => $sched) {
                if(array_key_exists($index_day,$series_section_name)){
                    $series_section_name[$index_day] .= "...Series ".($index_series)." [".date("M. d, y'", strtotime($sched["session_date"]))."]";
                }else{
                    $series_section_name[] = " ...Series ".($index_series)." [".date("M. d, y'", strtotime($sched["session_date"]))."]";
                }
            }
        }   

        if(array_key_exists(1, $series)){
            foreach($series[1] as $day => $sec){
                $sections[] = [
                    "type" => "webinar",
                    "data_id" => _current_webinar()->id,
                    "section_number" => $day+1,
                    "name" => $series_section_name[$day],
                    "objective" => "Students will learn a valueable lesson",
                    "created_by" => Auth::user()->id,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ];
            }
        }

        if(count($sections) > 0){
            Section::insert($sections);
        }
    }
}
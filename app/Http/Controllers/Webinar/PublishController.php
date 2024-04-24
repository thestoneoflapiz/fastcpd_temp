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
use App\Mail\{NotificationMail};

use Response; 
use Session;

class PublishController extends Controller
{
    function index(Request $request){
        if(_my_webinar_permission("publish")){
            $has_links = 0;

            $accreditation = _current_webinar()->accreditation ? json_decode(_current_webinar()->accreditation) : [];
            $accreditation = array_map(function($acc){
                $profession = Profession::select("title")->find($acc->id);
                $acc->title = $profession->title ?? "Not found";

                return $acc;
            }, $accreditation);

            $data["professions"] = $accreditation;

            $format = null;
            if(_current_webinar()->event=="day"){
                $schedule = Webinar_Session::where("webinar_id", "=", _current_webinar()->id)->get();
                if($schedule->count() > 0){
                    foreach ($schedule as $sched) {
                        if($sched->link){
                            $has_links++;
                        }

                        $format[] = [
                            "id" => $sched->id,
                            "date_" => $sched->session_date,
                            "sessions" => json_decode($sched->sessions),
                            "link" => $sched->link
                        ];
                    }
                }
                $data["schedule"] = $format;
            }else{
                $series = Webinar_Series::where("webinar_id", "=", _current_webinar()->id)->get();
                if($series->count() > 0){
                    foreach ($series as $srs) {
                        $json_sched = json_decode($srs->sessions);
                        $schedule = Webinar_Session::where("webinar_id", "=", _current_webinar()->id)
                            ->whereIn("id", $json_sched)->get();
                        if($schedule->count() > 0){
                            foreach ($schedule as $key => $sched) {
                                if($sched["link"]){
                                    $has_links++;
                                }

                                $format[] = [
                                    "series" => $srs->series_order,
                                    "id" => $sched["id"],
                                    "date_" => $sched->session_date,
                                    "sessions" => json_decode($sched->sessions),
                                    "link" => $sched["link"]
                                ];
                            }
                        }
                    }

                    $format = collect($format);
                }
                $data["schedule"] = $format ? $format->sortBy("series") : null;
            }

            $data["has_published"] = _current_webinar()->published_at ? true : false;
            $data["has_links"] = $has_links > 0 ? true : false;

            $data["without_price" ] = ($prices = _current_webinar()->prices) ? json_decode($prices)->without : 0;
          

            return view('page/webinar/management/publish', $data);
        }
        
        return view('template/errors/404');
    }

    function store(Request $request) : JsonResponse
    {
        $id = _current_webinar()->id;
        if($id){
            $published_date = date("Y-m-d H:i:s", strtotime($request->published_date));
            if($request->has("date_approved")){
                $date_approved = date("Y-m-d H:i:s", strtotime($request->date_approved));
            }else{
                $date_approved = date("Y-m-d H:i:s");
            }

            $webinar = Webinar::find($id);
            $webinar->published_at = $published_date;
            $webinar->approved_at = $date_approved; 

            if(_current_provider()->status!="approved"){
                if($webinar->prices){
                    $prices = json_decode($webinar->prices);
                    $prices->with = $webinar->offering_units!="without" ? $request->without_price : 0;
                    $prices->without = $webinar->offering_units!="with" ? $request->without_price : 0;
                    $webinar->prices = json_encode($prices);
                }else{
                    $webinar->prices = json_encode([
                        "with" => $webinar->offering_units!="without" ? $request->without_price : 0,
                        "without" => $webinar->offering_units!="with" ? $request->without_price : 0,
                    ]);
                }
            }else{
                if($webinar->offering_units!="without"){
                    $webinar->accreditation = json_encode($request->accreditation);
                }

                if($webinar->offering_units!="with"){
                    if($webinar->prices){
                        $prices = json_decode($webinar->prices);
                        $prices->without = $request->without_price;
                        $webinar->prices = json_encode($prices);
                    }else{
                        $webinar->prices = json_encode([
                            "with" => 0,
                            "without" => $request->without_price ?? 0,
                        ]);
                    }
                }
            }

            if($webinar->save()){
                return response()->json(["status" => 200, "message" => "Updated successfully!"]);
            }

            return response()->json(["status" => 500, "message" => "Unable to publish webinar! Please try again later"]);
        }

        return response()->json(["status" => 500, "message" => "Webinar not found! Please refresh your browser"]);
    }

    function index_links(Request $request){
        if(_my_webinar_permission("links")){
            $has_links = 0;

            $format = null;
            if(_current_webinar()->event=="day"){
                $schedule = Webinar_Session::where("webinar_id", "=", _current_webinar()->id)->get();
                if($schedule->count() > 0){
                    foreach ($schedule as $sched) {
                        if($sched->link){
                            $has_links++;
                        }

                        $format[] = [
                            "id" => $sched->id,
                            "date_" => $sched->session_date,
                            "sessions" => json_decode($sched->sessions),
                            "link" => $sched->link
                        ];
                    }
                }
                $data["schedule"] = $format;
            }else{
                $series = Webinar_Series::where("webinar_id", "=", _current_webinar()->id)->get();
                if($series->count() > 0){
                    foreach ($series as $srs) {
                        $json_sched = json_decode($srs->sessions);
                        $schedule = Webinar_Session::where("webinar_id", "=", _current_webinar()->id)
                            ->whereIn("id", $json_sched)->get();
                        if($schedule->count() > 0){
                            foreach ($schedule as $key => $sched) {
                                if($sched["link"]){
                                    $has_links++;
                                }

                                $format[] = [
                                    "series" => $srs->series_order,
                                    "id" => $sched["id"],
                                    "date_" => $sched->session_date,
                                    "sessions" => json_decode($sched->sessions),
                                    "link" => $sched["link"]
                                ];
                            }
                        }
                    }

                    $format = collect($format);
                }
                $data["schedule"] = $format ? $format->sortBy("series") : null;
            }

            $data["has_links"] = $has_links > 0 ? true : false;
            $data["sessions"] = Webinar_Session::where("webinar_id", "=", _current_webinar()->id)->get()->count();
            return view('page/webinar/management/links', $data);
        }
        
        return view('template/errors/404');
    }

    function store_links(Request $request) : JsonResponse
    {
        $id = _current_webinar()->id;
        if($id){
            if($request->has("links")){
                foreach($request->links as $link){
                    $session = Webinar_Session::find($link["id"]);
                    $session->link = $link["value"];
                    $session->updated_at = date("Y-m-d H:i:s");
                    $session->save();
                }
            }else{
                $sessions = Webinar_Session::where("webinar_id", "=", $id)->get();
                foreach ($sessions as $ses) {
                    $ses->link = null;
                    $ses->updated_at = date("Y-m-d H:i:s");
                    $ses->save();
                }

                $data = array(
                    'subject' => "Provider looking for Webinar Links",
                    'body' => "Hi Management!,<br/>Provider "._current_provider()->name." is asking to provide links for their webinar "._current_webinar()->title.". Please respond immediately.",
                    'recipient'=> "",
                    'link_button' => "",
                    'label_button' => "",
                );
                
                Mail::to("management@fastcpd.com")->send(new NotificationMail($data)) ;
            }

            return response()->json(["status" => 200, "message" => "Updated successfully!"]);
        }

        return response()->json(["status" => 500, "message" => "Webinar not found! Please refresh your browser"]);
    }

    function submit_review(Request $request) : JsonResponse
    {
        $error = [];
        
        if(!_webinar_creation_check("webinar_details")){ array_push($error,"Webinar Details"); }

        if(!_webinar_creation_check("attract_enrollments")){ array_push($error,"Attract Enrollments"); }

        if(!_webinar_creation_check("instructors")){ array_push($error,"Instructors"); }

        if(!_webinar_creation_check("video_content")){ array_push($error,"Webinar and Content"); }

        if(!_webinar_creation_check("handouts")){ array_push($error,"Handouts"); }

        if(!_webinar_creation_check("grading")){ array_push($error,"Grading & Assessment"); }

        if(!_webinar_creation_check("submit_accreditation")){ array_push($error, "PRC Accreditation");  }

        if(!_webinar_creation_check("publish")){ array_push($error,"Publish Webinar"); }

        if(count($error) > 0){
            return response()->json(["status" => 500, "message" => $error]);
        }

        $webinar = Webinar::find(_current_webinar()->id);
        $webinar->fast_cpd_status = "in-review";
        $user_provider= User::where("provider_id",$webinar->provider_id)->get();

        _notification_insert(
            "webinar_creation",
            $user_provider[0]->id,
            _current_webinar()->id,
            "Webinar Submission for review",
            "We'll review ". $webinar->title . " soon. We'll notify you once we've approvided it.  ",
            "/provider/webinars"

        );
        _send_notification_email($user_provider[0]->email,"webinar_for_review",_current_webinar()->id,$user_provider[0]->id);

        /////////////////superadmin
        foreach(_get_all_superadmin() as $superadmin){
            _notification_insert(
                "webinar_creation",
                $superadmin->id,
                _current_webinar()->id,
                "Webinar Submission for review",
                "New Webinar to Review ". $webinar->title,
                "/superadmin/verification/webinars"
    
            );

            _send_notification_email($superadmin->email,"webinar_for_review_superadmin",_current_webinar()->id,$superadmin->id);
        }
        /////////////////end

        foreach(json_decode(_current_webinar()->instructor_id) as $inst){
            if(Auth::user()->id != $inst && $user_provider[0]->id != $inst){
                $info = User::find($inst);
                _notification_insert(
                    "webinar_creation",
                    $inst,
                    _current_webinar()->id,
                    "Webinar Submission for review",
                    _current_webinar()->title." is being reviewd. We'll notify you once we've approved the webinar.",
                    "/provider/webinars"
                );
                _send_notification_email($info->email,"webinar_for_review",_current_webinar()->id,$inst);
            }
        }
        

        if($webinar->save()){
            return response()->json(["status" => 200, "message" => "Webinar has been successfully submitted for review!"]);
        }

        return response()->json(["status" => 500, "message" => "Unable to submit webinar for review! Please try again later"]);
    }
}
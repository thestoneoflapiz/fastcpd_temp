<?php

namespace App\Http\Controllers\Notification;

use App\{User, Provider, Co_Provider, Instructor, Course,Notification};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class NotificationController extends Controller
{
    function getNotif(Request $request) : JsonResponse  
    {
        if(isset(Auth::user()->id)){
            $arranged_data = [];
            $notif = Notification::where('recipient', Auth::user()->id)->orderBy('created_at','desc');
            $notif = $notif->take(10)->offset($request->page*10)->get();
            foreach($notif as $data){
                $arranged_data[] = array(
                    "link"=> $data->link,
                    "description"=> $data->description,
                    "time_ago"=> _get_time_difference($data->created_at->toDateTimeString()),
                    "read_at"=> $data->read_at,
                    "data_id"=> $data->data_id,
                    "notif_id"=> $data->id
                );
            }
            return response()->json(["data"=>$arranged_data], 200);
        }else{
            $arranged_data = [];
            
            return response()->json(["data"=>$arranged_data], 200);
        }
        
    }

    function getUnseenedNotif(Request $request) : JsonResponse  
    {
        if(isset(Auth::user()->id)){
            _notification_mark_seened();
            $notif = Notification::where('recipient', Auth::user()->id)
                            ->where('seen_at', null)
                            ->orderBy('created_at','desc')
                            ->get();
            if($notif){
                return response()->json(["data"=>count($notif)], 200);
            }else{
                return response()->json(["data"=>"0"], 200);
            }
        }else{
            $arranged_data = [];
            
            return response()->json(["data"=>"0"], 200);
        }
        
    }

    function redirect(Request $request) 
    {
        $notif_info = Notification::find($request->id);
        switch($notif_info->module) {
            case "course_creation":
                $course = Course::find($notif_info->data_id);
                session(["session_provider_id"=>Provider::select("id")->find($course->provider_id)]);
                Session::put("session_course_id",$request->id);
                $notif_info->read_at = date("Y-m-d H:i:s");
                $notif_info->save();
                return redirect(\config('app.link') . $notif_info->link);

            break;
            case "become_provider":
                $course = Course::find($notif_info->data_id);
                $notif_info->read_at = date("Y-m-d H:i:s");
                $notif_info->save();
                return redirect(\config('app.link') . $notif_info->link);

            break;
            case "voucher":
                $user = User::find($notif_info->recipient);
                session(["session_provider_id"=>Provider::select("id")->find($user->provider_id)]);
                $notif_info->read_at = date("Y-m-d H:i:s");
                $notif_info->save();

                return redirect(\config('app.link') . $notif_info->link);

            break;
            case "purchase":
                $user = User::find($notif_info->recipient);
                $notif_info->read_at = date("Y-m-d H:i:s");
                $notif_info->save();

                return redirect(\config('app.link') . $notif_info->link);

            break;
            default:
                $user = User::find($notif_info->recipient);
                $notif_info->read_at = date("Y-m-d H:i:s");
                $notif_info->save();
                return redirect(\config('app.link') );
        }
    }

    function redirect_email(Request $request) 
    {
        switch($request->module) {
            case "course_performance":
                $id = $request->id;
                session(["session_provider_id"=>Provider::select("id")->find($id)]);

                return redirect(\config('app.link') . '/provider/courses');
                break;
            default:
        }
    }

    function email_sample() 
    {
        $data = array(
            "table" => ""
        );
        // _send_notification_email("juncahilig08@gmail.com",'payout_completed','10','7');
        return view('/email/email_template');
        // _send_notification_email("m.cahilig.ipp@gmail.com","instructor_course_invitation",30,3);f
        
    }
}
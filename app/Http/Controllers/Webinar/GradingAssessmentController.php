<?php

namespace App\Http\Controllers\Webinar;

use App\Http\Controllers\Controller;
use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, 
    Webinar, Webinar_Instructor_Permission as WIP,
};

use Illuminate\Support\Facades\{Storage,Auth,DB};
use Illuminate\Http\{Request, JsonResponse};

use Response;
use Session;
use URL;

class GradingAssessmentController extends Controller
{
    function index(Request $request)
    {
        if(_my_webinar_permission("grading") && _webinar_creation_restricted("grading")){
            return view('page/webinar/management/grading');
        }
        return view('template/errors/404');
    }

    function store(Request $request) : JsonResponse
    {
        $id = _current_webinar()->id;
        if($id){
            $percentage = $request->percentage ?? 0;
            $retry = $request->retry;
            $assessment = $request->assessment;

            $assess = [
                "assessment" => $assessment,
                "retry" => $retry,
                "percentage" => ($percentage/100)
            ];
            
            $response = Webinar::where("id",$id)->update([
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
            return response()->json(["status" => 500, "message" => "Webinar Not Found" ]);
        }
    }

}
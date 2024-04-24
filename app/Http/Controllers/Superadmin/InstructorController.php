<?php

namespace App\Http\Controllers\Superadmin;

use App\{User, Provider, Co_Provider, Instructor, Course, Profile_Requests};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class InstructorController extends Controller
{
    function verification_list(Request $request) : JsonResponse
    {
        $profile_requests = Profile_Requests::select("profile_requests.id","profile_requests.created_at","profile_requests.link","users.name" )
                                ->whereIn("profile_requests.status", ["in-review", "pending"])
                                ->where(["profile_requests.type"=>"instructor"])
                                ->leftJoin("users","users.id","profile_requests.data_id")
                                ->get();
        if(count($profile_requests)){
            foreach($profile_requests as $request){
                $requests[]= [
                    "id" => $request->id,
                    "submitted_at" => date("M j, Y",strtotime($request->created_at)),                 
                    "submitted_by"=> $request->name,
                    "username" => $request->link,
                ];
            }
        }else{
            $requests = [];
        }
    
        return response()->json(["data"=>$requests, "total"=>count($requests)], 200);
    }

    function update_instructor(Request $request) : JsonResponse
    {
        if($request->state == "approve"){
            $new_data = Profile_Requests::find($request->id);
            $user = User::where("id",$new_data->data_id)
                        ->update([
                            "image" => $new_data->image,
                            "headline" => $new_data->headline,
                            "about" => $new_data->about,
                            "website" => $new_data->website,
                            "facebook" => $new_data->facebook,
                            "linkedin" => $new_data->linkedin,
                            "professions" => $new_data->professions,
                            "resume" => $new_data->resume,
                            "username" => $new_data->link,
                            "instructor" => "active",
                        ]);
            
            Profile_Requests::where("id",$request->id)
                            ->update([
                                "status" => "approved",
                            ]);
        }else{
            Profile_Requests::where("id",$request->id)
                            ->update([
                                "status" => "blocked",
                                "notes" => $request->notes,
                            ]);
        }
        
        return response()->json($request, 200); 
    }
}

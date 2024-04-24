<?php

namespace App\Http\Controllers\Webinar;

use App\Http\Controllers\Controller;
use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, 
    Webinar, Webinar_Instructor_Permission as WIP,
    Handout,
};

use Illuminate\Support\Facades\{Storage,Auth,DB};
use Illuminate\Http\{Request, JsonResponse};

use Response;
use Session;
use URL;

class HandoutController extends Controller
{
    function index(Request $request)
    {
        if(_my_webinar_permission("handouts") && _webinar_creation_restricted("handouts")){
            return view('page/webinar/management/handout');
        }
        
        return view('template/errors/404');
    }

    function allow(Request $request) : JsonResponse
    {
        $id = _current_webinar()->id;
        $allow = $request->allow;
        if($id){
            $webinar = Webinar::find($id);
            $webinar->allow_handouts = $allow=="true"?1:0;
            $webinar->save();
            return response()->json([]);
        }

        return response()->json([], 422);
    }

    function list(Request $request) : JsonResponse
    {
        $id = _current_webinar()->id;
        if(_current_webinar()->allow_handouts){
                $data = Handout::where([
                    "type" => "webinar",
                    "data_id" => $id,
                    "deleted_at" => null
                ])->get();
    
                return response()->json(["data" => $data, "allow" => true]);
        }

        return response()->json(["allow"=>false]);
    }

    function store(Request $request) : JsonResponse
    {
        $id = _current_webinar()->id;
        $handouts = $request->handouts;
        if($id){
            $handouts = array_map(function($hnd) use($id){
                $hnd["type"] = "webinar";
                $hnd["data_id"] = $id;
                $hnd["created_at"] = date("Y-m-d H:i:s");
                $hnd["created_by"] = Auth::user()->id;

                return $hnd;
            }, $handouts);

            Handout::insert($handouts);
            return response()->json(["status" => 200 , "message" => "Handouts successfully saved!"]);
        }
        
        return response()->json(["status" => 500, "message" => "Webinar not Found! Please refresh your browser" ]);

    }

    function remove(Request $request) : JsonResponse
    {
        $webinar = _current_webinar()->id;
        $id = $request->id;
        if($webinar){
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
                $filepath = "webinars/raw/provider".(_current_provider()->id)."/webinar".(_current_webinar()->id)."/handouts/{$name}";
                if($s3->delete($filepath)){
                    return response()->json([]);
                }
            }
        }

        return response()->json([], 422);
    }

    function upload(Request $request) : JsonResponse
    {
        $file = $request->file('files');
        $file_extension = $file->getClientOriginalExtension();
        $name = "webinar_handout_"._current_webinar()->id.uniqid().".{$file_extension}";
        $filepath = "webinars/raw/provider".(_current_provider()->id)."/webinar".(_current_webinar()->id)."/handouts/{$name}";
        $path = Storage::disk('s3')->put($filepath,file_get_contents($file));
        if($path){
            $pathname = Storage::disk('s3')->url($filepath);
            return response()->json(["pathname" => $pathname, "extension" => strtolower($file_extension), "filename"=> $name]);
        }
        return response()->json($path);
    }
}
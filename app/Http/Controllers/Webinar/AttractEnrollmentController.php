<?php

namespace App\Http\Controllers\Webinar;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, 
    Webinar, Webinar_Session,
    Webinar_Instructor_Permission as WIP,
    Section, Video, Article, Quiz,
    Image_Intervention,
};

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Storage,Auth,DB};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use \Aws\S3\{S3Client};

use AWS;
use Response;
use Image;
use Session;
use URL;

class AttractEnrollmentController extends Controller
{
    function index(Request $request){
        if(_my_webinar_permission("attract_enrollments") && _webinar_creation_restricted("attract_enrollments")){
            return view('page/webinar/management/attract');
        }
        
        return view('template/errors/404');
    }

    function upload_webinar_poster(Request $request) : JsonResponse
    {
        $s3disk = Storage::disk('s3');
        $s3Bucket = $s3disk->getDriver()->getAdapter()->getBucket();

        $original = $request->file('files');

        $new_filename = "webinar_"._current_webinar()->id.uniqid();
        $s3_filepath = "webinars/raw/provider".(_current_provider()->id)."/webinar".(_current_webinar()->id)."/poster";
        
        $original_size = storage_path("/temporary/image/{$new_filename}-original.jpg");
        $medium_size = storage_path("/temporary/image/{$new_filename}-medium.jpg");
        $small_size = storage_path("/temporary/image/{$new_filename}-small.jpg");

        Image::make($original)->resize(1080,600)->encode('jpg')->save($original_size);
        Image::make($original)->resize(750,422)->encode('jpg')->save($medium_size);
        Image::make($original)->resize(300,170)->encode('jpg')->save($small_size);
       
        $s3_url_original = "{$s3_filepath}/{$new_filename}-original.jpg";
        $s3_url_medium = "{$s3_filepath}/{$new_filename}-medium.jpg";
        $s3_url_small = "{$s3_filepath}/{$new_filename}-small.jpg";
        
        $original_upload = $s3disk->put($s3_url_original, fopen($original_size, "r+"));
        $medium_upload = $s3disk->put($s3_url_medium, fopen($medium_size, "r+"));
        $small_upload = $s3disk->put($s3_url_small, fopen($small_size, "r+"));

        if($original_upload && $medium_upload && $small_upload){
            $image_intervention = Image_Intervention::where([
                "type" => "webinar",
                "data_id" => _current_webinar()->id,
            ])->first();

            if($image_intervention){
            }else{
                $image_intervention = new Image_Intervention;
                $image_intervention->type = "webinar";
                $image_intervention->data_id =  _current_webinar()->id;
                $image_intervention->created_at = date("Y-m-d H:i:s");
            }

            $image_intervention->original_size = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_original";
            $image_intervention->medium_size = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_medium";
            $image_intervention->small_size = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_small";
            $image_intervention->save();

            $update = Webinar::find(_current_webinar()->id);
            $update->webinar_poster = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_original";
            $update->webinar_poster_id = $image_intervention->id;
            $update->save();

            unlink($original_size);
            unlink($medium_size);
            unlink($small_size);
            return response()->json(["pathname" => "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_original", "extension" => "jpg", "filename"=> "{$new_filename}-original.jpg"]);
        }
        
        return response()->json([], 422);
    }

    function remove_webinar_poster() : JsonResponse
    {
        $s3 = Storage::disk('s3');
        
        $webinar = Webinar::find(_current_webinar()->id);
        $webinar->webinar_poster = null;
        $webinar->webinar_poster_id = null;
        $webinar->save();

        /**
         * remove files from s3 bucket
         * 
         */
        $image_intervention = Image_Intervention::where([
            "type" => "webinar",
            "data_id" => _current_webinar()->id,
        ])->first();

        if($image_intervention){
            $images = [
                $image_intervention->original_size,
                $image_intervention->medium_size,
                $image_intervention->small_size,
            ];

            foreach($images as $img){
                $explode = explode("/", $img);
                $name = end($explode);
                $filepath = "webinars/raw/provider".(_current_provider()->id)."/webinar".(_current_webinar()->id)."/poster/{$name}";
                $s3->delete($filepath);
            }

            $image_intervention->delete();
        }
        
        return response()->json([]);
    }

    function upload_webinar_video(Request $request) : JsonResponse
    {
        $webinar = Webinar::find($request->webinar_id);
        if($webinar){
            $webinar->webinar_video = $request->file_url;
            $webinar->updated_at = date("Y-m-d H:i:s");
            $webinar->save();

            return response()->json([], 200);
        }

        return response()->json([
            "message" => "Unable to save video! Webinar not found, please refresh your browser!"
        ], 422);
    }

    function remove_webinar_video(Request $request) : JsonResponse
    {
        $s3 = Storage::disk('s3');

        $webinar = Webinar::find($request->webinar_id);
        if($webinar){

            $video_to_delete = $webinar->webinar_video;
            $webinar->webinar_video = null;
            $webinar->updated_at = date("Y-m-d H:i:s");

            $explode = explode("/", $video_to_delete);
            $name = end($explode);
            $filepath = "webinars/raw/provider{$request->provider_id}/webinar{$request->webinar_id}/video/{$name}";
            $s3->delete($filepath);
            $webinar->save();
            return response()->json([], 200);
        }

        return response()->json([
            "message" => "Unable to remove video! Webinar not found, please refresh your browser!"
        ], 422);
    }

    function store(Request $request) : JsonResponse{
    
        if(session::has("webinar_id")){
            $update = Webinar::find(_current_webinar()->id);
            $type = $request->store;

            switch ($type) {
                case 'objectives':
                    $update->objectives = json_encode($request->objectives);
                    break;

                case 'requirements':
                    $update->requirements = json_encode($request->requirements);
                    break;

                case 'target_students':
                    $update->target_students = json_encode($request->target_students);
                    break;
            }

            if($update->save()){
                return response()->json(["status" => 200, "message" => "Successfully saved!"]);
            }

            return response()->json(["status" => 500, "message" => "Unable to save updates! Please refresh your browser"]);
       }else{
        return response()->json(["status" => 500, "message" => "Webinar not found!"]);
       }
    }
}
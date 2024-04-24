<?php

namespace App\Http\Controllers\Course\Course_Creation;

use App\Http\Controllers\Controller;
use App\{
    User, Provider, Co_Provider, Instructor,
    Course, 
    Image_Intervention,
    Notification,
    Profession, 
};

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

    }

    function upload_course_poster(Request $request) : JsonResponse
    {
        $s3disk = Storage::disk('s3');
        $s3Bucket = $s3disk->getDriver()->getAdapter()->getBucket();

        $original = $request->file('files');

        $new_filename = "course_"._current_course()->id.uniqid();
        $s3_filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/poster";
        
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
                "type" => "course",
                "data_id" => _current_course()->id,
            ])->first();

            if($image_intervention){
            }else{
                $image_intervention = new Image_Intervention;
                $image_intervention->type = "course";
                $image_intervention->data_id =  _current_course()->id;
                $image_intervention->created_at = date("Y-m-d H:i:s");
            }

            $image_intervention->original_size = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_original";
            $image_intervention->medium_size = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_medium";
            $image_intervention->small_size = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_small";
            $image_intervention->save();

            $update = Course::find(_current_course()->id);
            $update->course_poster = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_original";
            $update->course_poster_id = $image_intervention->id;
            $update->save();

            unlink($original_size);
            unlink($medium_size);
            unlink($small_size);
            return response()->json(["pathname" => "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com/$s3_url_original", "extension" => "jpg", "filename"=> "{$new_filename}-original.jpg"]);
        }
        
        return response()->json([], 422);
    }

    function remove_course_poster() : JsonResponse
    {
        $s3 = Storage::disk('s3');
        
        $course = Course::find(_current_course()->id);
        $course->course_poster = null;
        $course->course_poster_id = null;
        $course->save();

        /**
         * remove files from s3 bucket
         * 
         */
        $image_intervention = Image_Intervention::where([
            "type" => "course",
            "data_id" => _current_course()->id,
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
                $filepath = "courses/raw/provider".(_current_provider()->id)."/course".(_current_course()->id)."/poster/{$name}";
                $s3->delete($filepath);
            }

            $image_intervention->delete();
        }
        
        return response()->json([]);
    }

    function upload_course_video(Request $request) : JsonResponse
    {
        $course = Course::find($request->course_id);
        if($course){
            $course->course_video = $request->file_url;
            $course->updated_at = date("Y-m-d H:i:s");
            $course->save();

            return response()->json([], 200);
        }

        return response()->json([
            "message" => "Unable to save video! Course not found, please refresh your browser!"
        ], 422);
    }

    function remove_course_video(Request $request) : JsonResponse
    {
        $s3 = Storage::disk('s3');

        $course = Course::find($request->course_id);
        if($course){

            $video_to_delete = $course->course_video;
            $course->course_video = null;
            $course->updated_at = date("Y-m-d H:i:s");

            $explode = explode("/", $video_to_delete);
            $name = end($explode);
            $filepath = "courses/raw/provider{$request->provider_id}/course{$request->course_id}/video/{$name}";
            $s3->delete($filepath);
            $course->save();
            return response()->json([], 200);
        }

        return response()->json([
            "message" => "Unable to remove video! Course not found, please refresh your browser!"
        ], 422);
    }
}
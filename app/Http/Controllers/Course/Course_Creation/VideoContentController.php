<?php

namespace App\Http\Controllers\Course\Course_Creation;

use App\Http\Controllers\Controller;
use App\{
    User, Provider, Co_Provider, Instructor,
    Course, 
    Notification,
    Profession, 
    Section, Video,
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

class VideoContentController extends Controller
{
    function index(Request $request){
        $sections = Section::where([
            "type"=>"course", "data_id"=> _current_course()->id,
            "deleted_at" => null,
        ])->get();

        $data = [
            'sections' => $sections,
        ];

        if(_my_course_permission("video_content") && _course_creation_restricted("video_content")){
            return view('page/course_creation/video-upload-js', $data);
            // return view('page/course_creation/video', $data);
        }
        
        return view('template/errors/404');  
    }

    function video_upload(Request $request){
        $video = Video::find($request->video_id);
        if($video){
            $duration = "01:00";

             /**
             * Getting Video Length
             * 
             */
            if ($fp_remote = fopen($request->file_url, 'rb')) {
                $localtempfilename = tempnam('/tmp', 'getID3');
                if ($fp_local = fopen($localtempfilename, 'wb')) {
                    while ($buffer = fread($fp_remote, 8192)) {
                        fwrite($fp_local, $buffer);
                    }
                    fclose($fp_local);
                    $getID3 = new \getID3;
                    $ThisFileInfo = $getID3->analyze($localtempfilename);
                    $duration = $ThisFileInfo['playtime_string'];
                    unlink($localtempfilename);
                }
            }


            $video->cdn_url = $request->file_url;
            $video->filename = $request->file_name;
            $video->size = $request->file_size;
            $video->length = $duration;
            $video->uploading_status = "done";
            $video->updated_at = date("Y-m-d H:i:s");
            $video->save();

            return response()->json([
                "data" => $video
            ], 200);
        }

        return response()->json([
            "message" => "Unable to save video! Video data not found, please refresh your browser!"
        ], 422);
    }
}
<?php

namespace App\Http\Controllers\Webinar;

use App\Http\Controllers\Controller;
use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, 
    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Instructor_Permission as WIP,
    Section, Video, Article, Quiz, Quiz_Item
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
            "type"=>"webinar", "data_id"=> _current_webinar()->id,
            "deleted_at" => null,
        ])->get();

        $data = [
            'sections' => $sections,
        ];

        if(_my_webinar_permission("video_content") && _webinar_creation_restricted("video_content")){
            return view('page/webinar/management/video', $data);
        }
        
        return view('template/errors/404');  
    }

    function content_sections(Request $request) : JsonResponse
    {
        $sections = Section::where([
            "type"=>"webinar", "data_id"=> _current_webinar()->id,
            "deleted_at" => null,
        ])->get();
        foreach ($sections as $key => $section) {
            $detailed_sequence = [];

            if($section->sequences){
                $sequence = json_decode($section->sequences);

                foreach ($sequence as $key => $seq) {
                    
                    switch ($seq->type) {
                        case 'video':
                            $video = Video::where([
                                "section_id"=>$section->id, 
                                "id"=>$seq->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
                            if($video){ $detailed_sequence[] = array_merge((array)$seq, $video->toArray()); }
                            break;
    
                        case 'article':
    
                            $article = Article::where([
                                "section_id"=>$section->id, 
                                "id"=>$seq->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
                            if($article){ $detailed_sequence[] = array_merge((array)$seq, $article->toArray()); }
                            break;
    
                        case 'quiz':
    
                            $quiz = Quiz::where([
                                "section_id"=>$section->id, 
                                "id"=>$seq->id, 
                                "deleted_at"=>null,
                            ])->first();
                            
                            if($quiz){ 
                                $quiz->items = Quiz_Item::where([
                                    "quiz_id" => $quiz->id,
                                    "deleted_at" => null,
                                ])->get();

                                $detailed_sequence[] = array_merge((array)$seq, $quiz->toArray()); 
                            }
                            break;
                    }
                }
            }

            $section->detailed_sequence = $detailed_sequence;
        }

        return response()->json(["data"=>$sections, "event" => _current_webinar()->event], 200);
    }

    function store_section(Request $request) : JsonResponse
    {
        $id = _current_webinar()->id;
        $objective = $request->objective;
        $section_id = $request->section_id;

        if($id){
            $section_exist = Section::where(["section_number" => $section_id, "type"=>"webinar", "data_id"=> $id, "deleted_at"=>null])->first();
            if($section_exist){
                $section_exist->objective = $objective;
                $section_exist->updated_at = date("Y-m-d H:i:s");
                $section_exist->save();

                return response()->json(["status" => 200, "message" => "Section successfully updated!", "exist"=> true]);
            }
         
            return response()->json(["status" => 500, "message" => "Section not found! Please refresh your browser"]);
        }else{
            return response()->json(["status" => 500, "message" => "Webinar not found! Please refresh your browser"]);
        }
    }

    function store_video(Request $request) : JsonResponse
    {
        $video_id = $request->video_id;
        $title = $request->title;
        $section = (int)$request->section;
        $part = (int)$request->part;

        $current_number_part = $request->current_number_part;
        $number_of_parts = $request->number_of_parts;

        $sec = Section::where([
            "type"=>"webinar", "data_id"=>  _current_webinar()->id,
            "section_number" => $section,
            "deleted_at" => null,
        ])->first();

        if($sec){
            if($video_id){
                $video = Video::find($video_id);
                $video->title = $title;
                $video->section_id = $sec->id;
                $video->poster = URL::to('/img/sample/poster-sample.png');
                $video->updated_at = date("Y-m-d H:i:s");
                $video->sequence_number = $current_number_part;
            }else{
                $video = new Video;
                $video->title = $title;
                $video->section_id = $sec->id;
                $video->poster = URL::to('/img/sample/poster-sample.png');
                $video->created_at = date("Y-m-d H:i:s");
                $video->updated_at = date("Y-m-d H:i:s");
                $video->created_by = Auth::user()->id;
                $video->sequence_number = $current_number_part;
            }
            
            if($video->save()){
                /**
                 * Replacement of sequence in section
                 * 
                 */
                if($sec->sequences){
                    $original_sequence = json_decode($sec->sequences);

                    $renew_sequence = [];
                    $recurring_part = 0;
                    $newpart = true;
                    foreach ($original_sequence as $index => $original) {
                        $recurring_part++;
                        if($original->part == $current_number_part){
                            if($original->id == $video->id && $original->type == "video"){
                                $renew_sequence[] = [
                                    "id" => $video->id,
                                    "part" => $recurring_part,
                                    "type" => "video",
                                ];
                                $newpart = false;
                            }else{
                                $renew_sequence[] = [
                                    "id" => $video->id,
                                    "part" => $recurring_part,
                                    "type" => "video",
                                ];
                                $recurring_part++;
                                $renew_sequence[] = [
                                    "id" => $original->id,
                                    "part" => $recurring_part,
                                    "type" => $original->type,
                                ];
                                $newpart = false;
                            }
                        }else{
                            $renew_sequence[] = [
                                "id" => $original->id,
                                "part" => $recurring_part,
                                "type" => $original->type,
                            ];
                        }
                    }

                    if($newpart){
                        $renew_sequence[] = [
                            "id" => $video->id,
                            "part" => $current_number_part,
                            "type" => "video",
                        ];
                    }

                    $sec->sequences = json_encode($renew_sequence);
                    $sec->save();
                }else{
                    $sec->sequences = json_encode([[
                        "part" => $current_number_part,
                        "type" => "video",
                        "id" => $video->id,
                    ]]);
                    $sec->save();
                }

                return response()->json(["status" => 200, "message" => "Video title has been saved!", "data"=>["url"=>false,"video_id"=>$video->id]]);
            }
            
            return response()->json(["status" => 500, "message" => "Unable to save video title! Please try again later."]);
        }

        return response()->json(["status" => 500, "message" => "Section not Found!"]);
    }

    function store_article(Request $request) : JsonResponse
    {
        $article_id = $request->article_id;
        $title = $request->title;
        $description = $request->textarea;
        $section = (int)$request->section;
        $part = (int)$request->part;

        $current_number_part = (int)$request->current_number_part;
        $number_of_parts = (int)$request->number_of_parts;

        $sec = Section::where([
            "type"=>"webinar", "data_id"=> _current_webinar()->id,
            "section_number" => $section,
            "deleted_at" => null,
        ])->first();

        if($sec){
            $read_time = _estimated_reading_time($description);

            if($article_id){
                $article = Article::find($article_id);
                $article->section_id = $sec->id;
                $article->sequence_number = $current_number_part;
                $article->title = $title;
                $article->description = $description;
                $article->reading_time = $read_time;
                $article->updated_at = date("Y-m-d H:i:s");
            }else{
                $article = new Article;
                $article->section_id = $sec->id;
                $article->sequence_number = $current_number_part;
                $article->title = $title;
                $article->description = $description;
                $article->reading_time = $read_time;
                $article->created_by = Auth::user()->id;
                $article->created_at = date("Y-m-d H:i:s");
                $article->updated_at = date("Y-m-d H:i:s");
            }
            
            if($article->save()){
                /**
                 * Replacement of sequence in section
                 * 
                 */
                if($sec->sequences){
                    $original_sequence = json_decode($sec->sequences);

                    $renew_sequence = [];
                    $recurring_part = 0;
                    $newpart = true;
                    foreach ($original_sequence as $index => $original) {
                        $recurring_part++;
                        if($original->part == $current_number_part){
                            if($original->id == $article->id && $original->type == "article"){
                                $renew_sequence[] = [
                                    "id" => $article->id,
                                    "part" => $recurring_part,
                                    "type" => "article",
                                ];
                                $newpart = false;
                            }else{
                                $renew_sequence[] = [
                                    "id" => $article->id,
                                    "part" => $recurring_part,
                                    "type" => "article",
                                ];
                                $recurring_part++;
                                $renew_sequence[] = [
                                    "id" => $original->id,
                                    "part" => $recurring_part,
                                    "type" => $original->type,
                                ];
                                $newpart = false;
                            }
                        }else{
                            $renew_sequence[] = [
                                "id" => $original->id,
                                "part" => $recurring_part,
                                "type" => $original->type,
                            ];
                        }
                    }

                    if($newpart){
                        $renew_sequence[] = [
                            "id" => $article->id,
                            "part" => $current_number_part,
                            "type" => "article",
                        ];
                    }

                    $sec->sequences = json_encode($renew_sequence);
                    $sec->save();
                }else{
                    $sec->sequences = json_encode([[
                        "part" => $current_number_part,
                        "type" => "article",
                        "id" => $article->id,
                    ]]);
                    $sec->save();
                }

                return response()->json(["status" => 200, "message" => "Article has been saved!", "data"=>["article_id"=>$article->id]]);
            }
            
            return response()->json(["status" => 500, "message" => "Unable to save article! Please try again later."]);
        }

        return response()->json(["status" => 500, "message" => "Section not Found!"]);
    }

    function store_quiz(Request $request) : JsonResponse
    {
        $quiz_id = $request->quiz_id;
        $title = $request->title;
        $section = (int)$request->section;
        $part = (int)$request->part;

        $current_number_part = (int)$request->current_number_part;
        $number_of_parts = (int)$request->number_of_parts;

        $sec = Section::where([
            "type"=>"webinar", "data_id"=> _current_webinar()->id,
            "section_number" => $section,
            "deleted_at" => null,
        ])->first();

        if($sec){
            if($quiz_id){
                $quiz = Quiz::find($quiz_id);
                $quiz->section_id = $sec->id;
                $quiz->sequence_number = $current_number_part;
                $quiz->title = $title;
                $quiz->updated_at = date("Y-m-d H:i:s");
            }else{
                $quiz = new Quiz;
                $quiz->section_id = $sec->id;
                $quiz->sequence_number = $current_number_part;
                $quiz->title = $title;
                $quiz->created_by = Auth::user()->id;
                $quiz->created_at = date("Y-m-d H:i:s");
                $quiz->updated_at = date("Y-m-d H:i:s");
            }
            
            if($quiz->save()){
                /**
                 * Replacement of sequence in section
                 * 
                 */
                if($sec->sequences){
                    $original_sequence = json_decode($sec->sequences);

                    $renew_sequence = [];
                    $recurring_part = 0;
                    $newpart = true;
                    foreach ($original_sequence as $index => $original) {
                        $recurring_part++;
                        if($original->part == $current_number_part){
                            if($original->id == $quiz->id && $original->type == "quiz"){
                                $renew_sequence[] = [
                                    "id" => $quiz->id,
                                    "part" => $recurring_part,
                                    "type" => "quiz",
                                ];
                                $newpart = false;
                            }else{
                                $renew_sequence[] = [
                                    "id" => $quiz->id,
                                    "part" => $recurring_part,
                                    "type" => "quiz",
                                ];
                                $recurring_part++;
                                $renew_sequence[] = [
                                    "id" => $original->id,
                                    "part" => $recurring_part,
                                    "type" => $original->type,
                                ];
                                $newpart = false;
                            }
                        }else{
                            $renew_sequence[] = [
                                "id" => $original->id,
                                "part" => $recurring_part,
                                "type" => $original->type,
                            ];
                        }
                    }

                    if($newpart){
                        $renew_sequence[] = [
                            "id" => $quiz->id,
                            "part" => $current_number_part,
                            "type" => "quiz",
                        ];
                    }

                    $sec->sequences = json_encode($renew_sequence);
                    $sec->save();
                }else{
                    $sec->sequences = json_encode([[
                        "part" => $current_number_part,
                        "type" => "quiz",
                        "id" => $quiz->id,
                    ]]);
                    $sec->save();
                }

                $no_items = Quiz_Item::where([
                    "quiz_id" => $quiz->id,
                    "deleted_at" => null,
                ])->get()->count();

                return response()->json(["status" => 200, "message" => "Quiz has been saved!", "data"=>["quiz_id"=>$quiz->id, "items" => $no_items]]);
            }
            
            return response()->json(["status" => 500, "message" => "Unable to save quiz! Please try again later."]);
        }

        return response()->json(["status" => 500, "message" => "Section not Found!"]);
    }

    function store_quiz_item(Request $request) : JsonResponse
    {
        $quiz_id = $request->quiz_id;
        $quiz_item_id = $request->quiz_item_id;
        $question = $request->question;
        $choices = $request->choices;
        $answer = $request->answer;
        $section = (int)$request->section;
        $part = (int)$request->part;

        $current_number_part = (int)$request->current_number_part;
        $number_of_parts = (int)$request->number_of_parts;

        $section_data = Section::where([
            "type"=>"webinar", "data_id"=> _current_webinar()->id,
            "section_number" => $section,"deleted_at" => null
            
        ])->first();

        if($section_data){
            $quiz = Quiz::where([ 
                "id" => $quiz_id,
                "deleted_at" => null,
            ])->first();
    
            if($quiz){
                $renew_choices = [];
                $total_reading_time = 0;
                foreach ($choices as $index => $choice) {
                    $total_reading_time += _estimated_reading_time($choice['description']);
                    if($index == $answer){
                        $renew_choices[] = [
                            "choice" => $choice['description'],
                            "explain" => $choice['explanation'] ?? null,
                            "answer" => true,
                        ];
                    }else{
                        $renew_choices[] = [
                            "choice" => $choice['description'],
                            "explain" => $choice['explanation'] ?? null,
                            "answer" => false,
                        ];
                    }
                }
    
                $quiz_item = Quiz_Item::where([
                    "id" => $quiz_item_id,
                    "quiz_id" => $quiz_id,
                    "deleted_at" => null,
                ])->first();      
    
                if($quiz_item){
                    $quiz_item->question = $question;
                    $quiz_item->choices = json_encode($renew_choices);
                    $quiz_item->answer = $answer;
    
                }else{
                    $quiz_item = new Quiz_Item;
                    $quiz_item->quiz_id = $quiz_id;
                    $quiz_item->question = $question;
                    $quiz_item->choices = json_encode($renew_choices);
                    $quiz_item->answer = $answer;
                    $quiz_item->created_by = Auth::user()->id;
                }
    
                if($quiz_item->save()){
                    $quiz->reading_time = $total_reading_time;
                    $quiz->save();
    
                    return response()->json(["status" => 200, "message" => "Quiz Item has been saved!", "data"=>["quiz_item"=>$quiz_item]]);
                }
    
                return response()->json(["status" => 500, "message" => "Unable to save quiz item! Please try again later."]);
            }

            return response()->json(["status" => 500, "message" => "Quiz not Found! Please refresh your browser"]);
        }
        return response()->json(["status" => 500, "message" => "Section not Found! Please refresh your browser"]);
    }

    function upload_textarea_image(Request $request) : JsonResponse
    {
        $image = $request->image;
        $filepath = "webinars/images/textarea/provider".(_current_provider()->id)."/webinar".(_current_webinar()->id)."/".time().$image->getClientOriginalName();
        $path = Storage::disk('s3')->put($filepath,file_get_contents($image));
        if($path){
            $pathname = Storage::disk('s3')->url($filepath);
            return response()->json(["image_url" => $pathname]);
        }
    }

    function remove_part(Request $request) : JsonResponse
    {
        $section_number = $request->section;
        $part = $request->part;
        $type = $request->type;
        $id = $request->id;

        $section = Section::where([
            "type"=>"webinar", "data_id"=> _current_webinar()->id,
            "section_number" => $section_number,
            "deleted_at" => null,
        ])->first();

        if($section){
            if($section->sequences){
                $original_sequence = json_decode($section->sequences);
                $renew_sequence = [];
                $recurring_part = 0;
                foreach ($original_sequence as $original) {
                    
                    if($original->id == $id && $original->part == $part && $original->type == $type){
                    }else{
                        switch($original->type){
                            case "video":
                                $video = Video::find($original->id);
                                if($video){
                                    if($video->deleted_at==null && $video->id != $id){
                                        $recurring_part++;
                                        $renew_sequence[] = [
                                            "id" => $original->id,
                                            "part" => $recurring_part,
                                            "type" => $original->type,
                                        ];
                                    }else{
                                        if($video->cdn_url){
                                            $keypath = "webinars/raw/provider".(_current_provider()->id)."/webinar".(_current_webinar()->id)."/{$video->filename}";
                                            $s3disk = Storage::disk('s3');
                                            $s3Bucket = $s3disk->getDriver()->getAdapter()->getBucket();
                                            $exists = $s3disk->exists($keypath);
                            
                                            if($exists){
                                               $s3disk->delete($keypath);
                                            }  
                                        }
                                    }
                                }
                            break;

                            case "article":
                                $article = Article::find($original->id);
                                if($article && $article->deleted_at==null){
                                    $recurring_part++;
                                    $renew_sequence[] = [
                                        "id" => $original->id,
                                        "part" => $recurring_part,
                                        "type" => $original->type,
                                    ];
                                }
                            break;

                            case "quiz":
                                $quiz = Quiz::find($original->id);
                                if($quiz && $quiz->deleted_at==null){
                                    $recurring_part++;
                                    $renew_sequence[] = [
                                        "id" => $original->id,
                                        "part" => $recurring_part,
                                        "type" => $original->type,
                                    ];
                                }
                            break;
                        }
                    }
                }

                $section->sequences = count($renew_sequence) > 0 ? json_encode($renew_sequence) : null; 
                if($section->save()){

                    switch ($type) {
                        case 'video':
                            $video = Video::find($id);
                            if($video){
                                $video->deleted_at = date("Y-m-d H:i:s");
                                $filename = $video->filename;
                                $video->save();
    
                                if($video->cdn_url){
                                    /**
                                     * remove video from s3 bucket
                                     * 
                                     */
                                    $s3 = Storage::disk('s3');
                                    $filepath = "webinars/raw/provider".(_current_provider()->id)."/webinar".(_current_webinar()->id)."/{$filename}";
                                    if($s3->delete($filepath)){
                                        return response()->json(["status"=>200, "message"=> "Video has been removed successfully!"], 200);
                                    }
                                }

                                return response()->json(["status"=>200, "message"=> "Video has been removed successfully!"], 200);
                                
                            }
                            break;
    
                        case 'article':
                            $article = Article::find($id);
                            if($article){
                                $article->deleted_at = date("Y-m-d H:i:s");
                                $article->save();
                            }

                            return response()->json(["status"=>200, "message"=> "Article has been removed successfully!"], 200);
                            break;
    
                        case 'quiz':
                            $quiz = Quiz::find($id);
                            if($quiz){
                                $quiz->deleted_at = date("Y-m-d H:i:s");
                                $quiz->save();
                            }

                            return response()->json(["status"=>200, "message"=> "Quiz has been removed successfully!"], 200);
                            break;
                    }
                }
                return response()->json(["status"=>500, "message"=> "Unable to remove {$type}. Please refresh your browser"], 200);
            }
            return response()->json(["status"=>200, "message"=> ucwords($type)." has been removed successfully!"], 200);
        }
        return response()->json(["status"=>500, "message"=> "Something went wrong! Please refresh your browser"], 200);
    }
}

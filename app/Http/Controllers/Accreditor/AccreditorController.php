<?php

namespace App\Http\Controllers\Accreditor;

use App\{
    User, Profession, Provider, Instructor, Co_Provider, 
    Handout, Section, Video, Article, Quiz, Quiz_Item,

    Webinar, Course,
    Webinar_Series, Webinar_Session,

    Image_Intervention,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Support\Collection;
use App\Mail\{AccreditorApproval};

use Response; 
use Session; 
 
class AccreditorController extends Controller
{
    function index(){
        $data_record = null;
        $data_type = null;
        
        $course = Course::where([
            "deleted_at" => null,
        ])->whereRaw("JSON_CONTAINS(submit_accreditation_evaluation, '{\"email\": \"".Auth::user()->email."\"}')")->first();

        if($course){
            $course->poster = _get_image("course", $course->id)["small"];
            $data_record = $course;

            $data_type = "course";
        }else{
            $webinar = Webinar::where([
                "deleted_at" => null,
            ])->whereRaw("JSON_CONTAINS(submit_accreditation_evaluation, '{\"email\": \"".Auth::user()->email."\"}')")->first();

            if($webinar){
                $webinar->poster = _get_image("webinar", $webinar->id)["small"];
                $webinar->schedule = $schedule = _webinar_schedule($webinar->id, $webinar->event);
                $webinar->total = _webinar_total($webinar->id, $webinar->event);
                
                $data_record = $webinar;
                $data_type = "webinar";
            }
        }

        $professions = Profession::select("title", "id")->whereIn("id", $data_record->profession_id ? json_decode($data_record->profession_id) : [])->get();
        $data = [
            "data" => $data_record,
            "type" => $data_type,
            "professions" => $professions,
        ];
        return view('page/accreditor/index', $data);
    }

    function submit_review(Request $request) : JsonResponse
    {
        if($request->type == "course"){
            $course_id = $request->data_id;
            $accreditation = $request->accreditation;
            $course = Course::find($course_id);
            $provider = Provider::select("id", "name", "logo", "email", "url", "headline")->find($course->provider_id);
    
            $course->accreditation = json_encode($accreditation);
            $course->prc_status = "approved";
            $course->approved_at = date("Y-m-d H:i:s");
            
            /**
             * temporary data
             * 
             */
            $course->total_unit_amounts = $accreditation[0]["units"];
            $course->program_accreditation_no = $accreditation[0]["program_no"];
    
            if($course->save()){
                Auth::logout();
                if($provider){
                    $course->type == "course";
                    Mail::to($provider->email)->send(new AccreditorApproval($course, $provider));
                }
                return response()->json([]);
            }
    
            return response()->json([], 422);
        }else{
            $webinar_id = $request->data_id;
            $accreditation = $request->accreditation;
            $webinar = Webinar::find($webinar_id);
            $provider = Provider::select("id", "name", "logo", "email", "url", "headline")->find($webinar->provider_id);
    
            $webinar->accreditation = json_encode($accreditation);
            $webinar->prc_status = "approved";
            $webinar->approved_at = date("Y-m-d H:i:s");

            if($webinar->save()){
                Auth::logout();
                if($provider){
                    $webinar->type == "webinar";
                    Mail::to($provider->email)->send(new AccreditorApproval($webinar, $provider));
                }
                return response()->json([]);
            }
    
            return response()->json([], 422);
        }
       
    }

    function live_course_preview(Request $request)
    {
        $course = Course::with("provider")->where(["url"=>$request->url])->first();
        if($course){

            // if(_course_creation_check("course_details") && _course_creation_check("attract_enrollments") && _course_creation_check("video_content")){

                $handouts = Handout::where([
                    "type" => "course",
                    "data_id" => $course->id,
                    "deleted_at" => null
                ])->get();
    
                if($course->instructor_id!=null){
                    $instructors = Instructor::with("profile")->whereIn("id", json_decode($course->instructor_id))->where([
                        "provider_id" => $course->provider_id,
                    ])->get();
                }else{
                    $instructors = [];
                }
    
                $img_handout = [
                    "pdf" => "https://www.fastcpd.com/img/pdf.png",
                    "xls" => "https://www.fastcpd.com/img/excel.png",
                    "csv" => "https://www.fastcpd.com/img/excel.png",
                    "zip" => "https://www.fastcpd.com/img/folder.png",
                    "other" => "https://www.fastcpd.com/img/document.png"
                ];
    
                $total_values = $this->total_video_quiz($course->id);
                $data = [
                    "data" => $course,
                    "course" => $course,
                    "instructors" => $instructors,
                    "progress" => null,
                    "handouts" => $handouts,
                    "handout_img" => $img_handout,
                    "certificate" => null,
                    "rating" => null,
                    "total" => array (
                        "video_hours" => $total_values['video_hours'],
                        "quizzes" => $total_values['quizzes'],
                        "handouts" => $handouts->count(),
                    )
                ];
                return view('page/accreditor/live', $data);
            // }
        }
    }

    function total_video_quiz($course_id){
        
        $hours = 0;
        $minutes = 0;
        $total_quizzes = 0;

        $sections = Section::where([
            "type"=>"course", "data_id"=> $course_id,
            "deleted_at" => null,
        ])->get();

        foreach ($sections as $key => $section) {
            $sequence = json_decode($section->sequences);
            foreach ($sequence as $key => $seq) {
                switch ($seq->type) {
                    case 'video':
                        $video = Video::where([
                            "section_id"=>$section->id, 
                            "deleted_at"=>null,
                        ])->first();

                        if($video){ $minutes += floatval(str_replace(":", ".", $video->length)); }
                        break;

                    case 'quiz':

                        $quiz = Quiz::where([
                            "section_id"=>$section->id, 
                            "deleted_at"=>null,
                        ])->first();
                        
                        if($quiz){ 
                            $quiz_items = Quiz_Item::select("id", "question", "choices", "answer")->where([
                                "quiz_id" => $quiz->id,
                                "deleted_at" => null,
                            ])->get()->toArray();

                            if(count($quiz_items) > 0 ){ $total_quizzes++; }
                        }
                        break;
                }
            }
        }

        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        return [
            "video_hours" => ($hours < 10 ? "0{$hours}" : $hours).":".($minutes < 10 ? "0{$minutes}" : $minutes),
            "quizzes" => $total_quizzes
        ];
    }

    function webinar_page(Request $request)
    {
        $webinar = Webinar::select(
			"id", "provider_id", "profession_id", "instructor_id",
			"url", "title", "headline", "description",
			"event", "offering_units", "prices",
			"webinar_poster_id", "webinar_poster", "webinar_video",
			"objectives", "requirements", "target_students",
			"accreditation", "allow_handouts", 
			"assessment", "language", "type", "target_number_students"
		)->where([
            "deleted_at" => null,
            "url" => $request->url,
		])->first();

		if ($webinar) {
			$webinar->prices = json_decode($webinar->prices);
			$webinar_posters = Image_Intervention::find($webinar->webinar_poster_id);
			$schedule = _webinar_schedule($webinar->id, $webinar->event);
			$total = _webinar_total($webinar->id, $webinar->event);
			$sections =_webinar_sections($webinar->id, $webinar->event, $schedule);
			$provider = $this->webinar_provider($webinar->provider_id);
			$accreditation = $this->webinar_accreditation($webinar->accreditation);

			if($webinar->event == "day"){
				$data = [
					"provider" => $provider,
					"webinar" => $webinar,
					"posters" => $webinar_posters,
					"schedule" => $schedule,
					"sections" => $sections,
					"total" => $total,
					"accreditation" => $accreditation,
				];
			}else{
				$data = [
					"provider" => $provider,
					"webinar" => $webinar,
					"posters" => $webinar_posters,
					"schedule" => $schedule ? $schedule[0]["sessions"] : [], 
					"schedules" => $schedule,
					"sections" => $sections,
					"total" => $total,
					"accreditation" => $accreditation,
				];
            }

            return view("page/webinar/superadmin/page", $data);
		}
    }

    function webinar_provider($id){
		$provider = Provider::find($id);
		if($provider){
			$provider->webinar_total = Webinar::where("provider_id", "=", $id)
			->whereIn("fast_cpd_status", ["approved", "published", "live", "ended"])->get()->count();

			$provider->course_total = Course::where("provider_id", "=", $id)
			->whereIn("fast_cpd_status", ["approved", "published", "live", "ended"])->get()->count();

			$provider->instructor_total = Instructor::where([
				"provider_id" => $id,
				"status" => "active"
			])->get()->count();
		}

		return $provider;
	}

	function webinar_accreditation($accreditation){
		$accreditation_new = [];
		if($accreditation){
			$accreditation = json_decode($accreditation);
			foreach ($accreditation as $acc) {
				$profession = _get_profession($acc->id);
				$accreditation_new[] = [
					"id" => $profession->id,
					"profession" => $profession->profession,
					"units" => $acc->units,
					"program_no" => $acc->program_no
				];
			}
		}

		return $accreditation_new;
    }
    
    function webinar_live(Request $request)
    {
        $handouts = null;
        $webinar = Webinar::with("provider")->where(["url"=>$request->url])->first();
        if($webinar){
            $session = _webinar_session($webinar);
            if($session==null){
                return view('template/errors/course', ["type" => "webinar"]);
            }

            if($webinar->allow_handouts==1){
                $handouts = Handout::where([
                    "type" => "webinar",
                    "data_id" => $webinar->id,
                    "deleted_at" => null
                ])->get();
            }
            
            if($webinar->instructor_id!=null){
                $instructors = Instructor::with("profile")->whereIn("id", json_decode($webinar->instructor_id))->where([
                    "provider_id" => $webinar->provider_id,
                ])->get();
            }else{
                $instructors = [];
            }

            $img_handout = [
                "pdf" => "https://www.fastcpd.com/img/pdf.png",
                "xls" => "https://www.fastcpd.com/img/excel.png",
                "csv" => "https://www.fastcpd.com/img/excel.png",
                "zip" => "https://www.fastcpd.com/img/folder.png",
                "other" => "https://www.fastcpd.com/img/document.png"
            ];

            $data = [
                "data" => $webinar,
                "webinar" => $webinar,
                "session" => $session,
                "attendance" => null,
                "instructors" => $instructors,
                "progress" => null,
                "handouts" => $handouts,
                "handout_img" => $img_handout,
                "rating" => null,
                "total" => array (
                    "quizzes" => _webinar_total_quizzes($webinar->id),
                    "handouts" => $handouts ? $handouts->count() : 0,
                ),
                "certificates" => null,
                "type" => "webinar",
            ];

            return view('page/webinar/accreditor/live', $data);
        }

        return view('template/errors/404');
    }

    function get_sections(Request $request) : JsonResponse
    {
        $webinar = Webinar::select("id", "event")->find($request->webinar_id);
        if($webinar){
            switch ($webinar->event) {
                case 'day':
                    $sections = Section::where([
                        "type" => "webinar",
                        "data_id" => $webinar->id,
                        "deleted_at" => null,
                    ])->get();
                    
                    return response()->json($this->webinar_section($sections));
                break;

                case 'series':
                    $schedule = _webinar_schedule($webinar->id, $webinar->event);
                    $current = _webinar_session($webinar);
                    $session_section_order = 0;
                    foreach($schedule as $sched){
                        if($sched["sessions"]){
                            foreach($sched["sessions"] as $key => $sess){
                                if($sess["id"] == $current->id){
                                    $session_section_order = $key+1;
                                }
                            }
                        }
                    }   

                    $sections = Section::where([
                        "type" => "webinar",
                        "data_id" => $webinar->id,
                        "section_number" => $session_section_order,
                        "deleted_at" => null,
                    ])->get();
                    
                    return response()->json($this->webinar_section($sections));
                break;
            }
        }
    }

    function course_page(Request $request)
    {
        $course = Course::with("provider")->where("url","=", $request->url)->first();
        $data = [];
        if ($course) {
            $professions = Profession::select("title", "id")->whereIn("id", $course->profession_id ? json_decode($course->profession_id) : [])->get();
            $sections = Section::where([
                "type"=>"course", "data_id"=> $course->id,
                "deleted_at" => null,
            ])->where("sequences", "!=", null)->get();
            
            $sections = array_map(function($sec){
                $sequence = json_decode($sec['sequences']);
                $rearannged = [];
                foreach ($sequence as $key => $seq) {
                    switch ($seq->type) {
                        case 'video':
                            $video = Video::where([
                                "id" =>  $seq->id,
                                "deleted_at" => null,
                            ])->first();
                            
                            if($video){
                                $rearannged[] = [
                                    "type" => $seq->type,
                                    "title" => $video->title,
                                    "minute" => $video->length
                                ];
                            }

                            break;

                        case 'article':
                            $article = Article::where([
                                "id" =>  $seq->id,
                                "deleted_at" => null,
                            ])->first();

                            if($article){
                                $rearannged[] = [
                                    "type" => $seq->type,
                                    "title" => $article->title,
                                    "minute" => $article->reading_time
                                ];
                            }
                            break;

                        case 'quiz':
                            $quiz = Quiz::where([
                                "id" =>  $seq->id,
                                "deleted_at" => null,
                            ])->first();
                            
                            if($quiz){
                                $quiz_items = Quiz_Item::where([
                                    "quiz_id" => $quiz->id,
                                    "deleted_at" => null
                                ])->get()->count();
                                
                                $rearannged[] = [
                                    "type" => $seq->type,
                                    "title" => $quiz->title,
                                    "minute" => $quiz_items
                                ];
                            }
                            
                            break;
                    }
                }

                $sec['arranged_parts'] = $rearannged;

                return $sec;
            }, $sections->toArray());

            $instructors = User::whereIn("id", ($course->instructor_id ? json_decode($course->instructor_id) : []))->get();
            $data = [
                "course" => $course,
                "professions" => $professions,
                "sections" => $sections,
                "instructors" => $instructors,
                "part" => _course_content_parts_length($course->id),
                "total" => [
                    "video" => _course_total_video_length($course->id),
                    "quiz" => _course_total_quizzes($course->id),
					"article" => _course_total_article($course->id),
					"handout" => $course->allow_handouts === 1 ? _course_total_handout($course->id)->count() : 0,
                ],
                "data" => $course,
                "type" => "course",
            ];
        }
        return view('page/accreditor/course', $data);
    }
}

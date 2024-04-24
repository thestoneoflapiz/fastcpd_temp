<?php

namespace App\Http\Controllers\Provider;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Course, Instructor_Permissions, Image_Intervention,
    Webinar,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class CourseController extends Controller
{
    function add(Request $request) : JsonResponse
    {
        $provider_id = $request->provider_id;
        $title = $request->title;
        $url = $request->course_url;
        $professions = $request->profession;
        
        $this->validate($request, [
            'course_url' => 'required|unique:courses,url',
        ]);

        $state = false;
        $instructor_id[] = Auth::user()->id ;
        $course = new Course;
        $course->provider_id = $provider_id;
        $course->title = $title;
        $course->url = $url;
        $course->profession_id = json_encode($professions);
        $course->prc_status = "draft";
        $course->fast_cpd_status = "draft";
        if(_is_provider_instructor(Auth::user()->id,_current_provider()->id)){
            $course->instructor_id = json_encode([(string)Auth::user()->id]);
            $state = true;
        }
        $course->created_at = date("Y-m-d H:i:s");
        $course->created_by = Auth::user()->id;
        
        if($course->save()){
            if($request->has("converted") && $request->converted){
                $webinar = Webinar::select(
                    "headline", "description", "webinar_poster", "webinar_poster_id",
                    "webinar_video", "objectives", "requirements", "target_students",
                    "language"
                )->find($request->previous_id);
                $course->headline = $webinar->headline;
                $course->description = $webinar->description;
                $course->course_poster = $webinar->webinar_poster;
                $course->course_poster_id = $webinar->webinar_poster_id;
                $course->course_video = $webinar->webinar_video;
                $course->objectives = $webinar->objectives;
                $course->requirements = $webinar->requirements;
                $course->target_students = $webinar->target_students;
                $course->language = $webinar->language;
                    
                $course->save();

                $previous_intervention = Image_Intervention::find($webinar->webinar_poster_id);
                if($previous_intervention){
                    Image_Intervention::insert([
                        "type" => "course",
                        "data_id" => $course->id,
                        "original_size" => $previous_intervention->original_size,
                        "medium_size" => $previous_intervention->medium_size,
                        "small_size" => $previous_intervention->small_size,
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s"),
                    ]);
                }
            }

            $request->session()->put('course_id', $course->id);
            $request->session()->put('session_course_id', $course->id);
            if($state){
                Instructor_Permissions::insert([
                    "user_id" =>  Auth::user()->id,
                    "course_id" => _current_course()->id,
                    "course_details" => 1,
                    "attract_enrollments" => 1,
                    "instructors" => 1,
                    "video_and_content" => 1,
                    "handouts" => 1,
                    "grading_and_assessment" => 1,
                    "submit_for_accreditation" => 1,
                    "price_and_publish" => 1,
                    "created_by" => Auth::user()->id,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_by" => Auth::user()->id,
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
            }

            return response()->json([], 200);
        }

        return response()->json(["msg"=>"Unable to save course. Please try again later!"], 422);
    }

    function list(Request $request) : JsonResponse
    {   
        $provider_id = $request->provider_id;
        $pagination = $request->pagination;
        
        $order = $pagination["order"];
        $search = $pagination["search"];
        $page = $pagination["page"];
        $take = $pagination["take"];
        $offset = $page * $take;
        
        if(Auth::user()->provider_id != $provider_id){
            $total = Course::select("provider_id")->where(["provider_id"=>$provider_id, "deleted_at"=>null])
                ->whereIn("fast_cpd_status", ["draft", "in-review", "approved", "published", "live"])
                ->whereIn("prc_status", ["draft", "in-review", "approved"])
                ->whereRaw("(JSON_CONTAINS(instructor_id, '\"".Auth::user()->id."\"') OR created_by = '".Auth::user()->id."')");

            $courses = Course::select("total_unit_amounts","program_accreditation_no","id","provider_id","url","course_poster","title","prc_status","fast_cpd_status","created_at","created_by", "price")
                ->where(["provider_id"=>$provider_id, "deleted_at"=>null])
                ->whereIn("fast_cpd_status", ["draft", "in-review", "approved", "published", "live"])
                ->whereIn("prc_status", ["draft", "in-review", "approved"])
                ->whereRaw("(JSON_CONTAINS(instructor_id, '\"".Auth::user()->id."\"') OR created_by = '".Auth::user()->id."')");
        }else{
            $total = Course::select("provider_id")->where(["provider_id"=>$provider_id, "deleted_at"=>null])
                ->whereIn("fast_cpd_status", ["draft", "in-review", "approved", "published", "live"])
                ->whereIn("prc_status", ["draft", "in-review", "approved"]);

            $courses = Course::select("total_unit_amounts","program_accreditation_no","id","provider_id","url","course_poster","title","prc_status","fast_cpd_status","created_at","created_by", "price")
                ->where(["provider_id"=>$provider_id, "deleted_at"=>null])
                ->whereIn("fast_cpd_status", ["draft", "in-review", "approved", "published", "live"])
                ->whereIn("prc_status", ["draft", "in-review", "approved"]);
        }
        
        /**
         * Search title
         * 
         */
        if($search){
            $courses = $courses->where("title", "like", "%{$search}%");
            $total = $total->where("title", "like", "%{$search}%");
        }

        /**
         * Order BY
         * 
         */
        switch ($order) {
            case 'newest':
                $courses = $courses->orderBy("created_at", "desc");
                break;

            case 'oldest':
                $courses = $courses->orderBy("created_at", "asc");
                break;

            case 'from_a':
                $courses = $courses->orderBy("title", "asc");
                break;

            case 'from_z':
                $courses = $courses->orderBy("title", "desc");
                break;
            
            default: // fast_cpd_status
                $courses = $courses->orderBy("fast_cpd_status", "asc")->orderBy("prc_status", "asc");
                break;
        }

        /**
         * Pagination
         * 
         */

        $courses = $courses->take($take)->offset($offset)->get();
        $courses = array_map(function($course){

            $current_month_progress = $this->current_month_progress($course["id"]);

            $course["total_progress"] = _course_progress($course["id"]);

            $course["total_earning_month"] = $current_month_progress['current_month_overview'] ? $current_month_progress['current_month_overview']->current_revenue != null ? $current_month_progress['current_month_overview']->current_revenue : 0 : 0;
            $course["total_enrollment_month"] = $current_month_progress['current_month_overview'] ? $current_month_progress['current_month_overview']->current_enrolled : 0; 
            $course["total_course_rating"] = $current_month_progress['current_rating'] ? $current_month_progress['current_rating'] : 0;
            
            return $course;
        }, $courses->toArray());


        return response()->json(["data"=>$courses, "total"=>$total->get()->count()], 200);
    }

    function suggestions(Request $request) : JsonResponse
    {

        $title = $request->title;
        $remove_unwanted = preg_replace("/[^a-zA-Z-0-9]/", "-", $title);
        $remove_duplicate = preg_replace('/([-])\\1+/', '$1', $remove_unwanted);
        $trim_dash = trim($remove_duplicate, "-");
        $trim_dash = strtolower($trim_dash);
        
        $random_addons = ["watch", "live", "new", "course", "learn", "best", "good"];
        $suggestions = [ $trim_dash, date('Y-').$trim_dash, $trim_dash.date('-Y')];
        $suggestions = array_map(function($value) use($random_addons){
            $find = Course::where("url", "=", $value)->first();
            if($find){
                $new_value = $random_addons[rand(0, 6)]."-{$value}";
                $find_new = Course::where("url", "=", $value)->first();

                if($find_new){
                    $random_rand = ["{$new_value}-".date("dm"), date("dm")."-{$new_value}"];
                    return $random_rand[rand(0,1)];
                }

                return $new_value;
            }

            return $value;
        }, $suggestions);
        
        
        return response()->json(["data" => $suggestions], 200);
    }
    
    function current_month_progress($course_id){
        $provider_id = _current_provider();
        $current_month_overview = Course::select(
                            DB::raw('sum(purchase_items.provider_revenue) as current_revenue'),
                            DB::raw('count(purchase_items.id) as current_enrolled')
                        )->whereMonth("purchase_items.updated_at",date("m"))
                        ->whereYear("purchase_items.updated_at",date("Y"))
                        ->where("providers.id",$provider_id->id)
                        ->where("courses.id",$course_id)
                        ->where("purchase_items.payment_status","paid")
                        ->where("purchase_items.fast_status","complete")
                        ->where("purchase_items.type","course")
                        ->leftJoin("providers","providers.id","courses.provider_id")
                        ->leftJoin("purchase_items","purchase_items.data_id","courses.id")
                        ->leftJoin("users","users.id","purchase_items.user_id")->first();
        return [
            "current_rating" =>  _get_avg_rating("course", $course_id),
            "current_month_overview" => $current_month_overview
        ];
    }
}

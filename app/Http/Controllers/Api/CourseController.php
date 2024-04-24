<?php

namespace App\Http\Controllers\Api;

use App\{
    User, Profession, Provider, Instructor, Course, Co_Provider, 
    System_Rating_Report, Course_Rating,
    Course_Progress, Quiz, Quiz_Item, Video, Article,
    Section,

    My_Cart, 
    Purchase, Purchase_Item,

    Webinar,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Support\Collection;

use DateTime;
use Response; 
use Session; 

class CourseController extends Controller
{
    /**
     * profession
     * taking = Number or none
     * top = get top course or none
     * 
     */
    function by_profession(Request $request) : JsonResponse 
    {
        /**
         * 
         * This functionality is temporary for the highest number of courses 
         * until system rating report is functioning well
         * 
         */
        $profession = $request->profession;

        $taking = $request->taking;
        $page = $request->page;
        $offset = $page * $taking;

        if($profession){
            
            $total = Course::select("id")->where(["prc_status" => "approved" ])->whereIn("fast_cpd_status", ["published","live", "ended"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->take(20)->get();
            $courses = Course::select("accreditation", "program_accreditation_no","provider_id", "id", "url", "profession_id", "instructor_id", "price", "course_poster", 
            "title", "headline", "marketing_description","objectives", "requirements", "target_students", "assessment",
            "pass_percentage", "total_unit_amounts", "session_start", "session_end", "language",
            "prc_status", "fast_cpd_status")->where([
                "prc_status" => "approved"
            ])->whereIn("fast_cpd_status", ["published","live", "ended"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")
            ->whereIn("id", $total);

            if($taking){
                $courses = $courses->offset($offset)->take($taking);
            }

            $data = array_map(function($course){
                $course["course_poster"] = _get_image("course", $course["id"])["medium"];

                $pro_ids = $course["profession_id"] ? json_decode($course["profession_id"]) : [];
                $inst_ids = $course["instructor_id"] ? json_decode($course["instructor_id"]) : [];

                $pros = Profession::select("title")->whereIn("id", $pro_ids)->get()->toArray();
                $ints = Instructor::select("user_id as id")->whereIn("id", $inst_ids)->get()->toArray();
                $ints_name = User::select("name")->whereIn("id", $ints)->get()->toArray();

                $course["professions"] = "";
                $course["instructors"] = "";

                foreach ($pros as $key => $pr) {
                    if(count($pros) == $key+1){ $course["professions"] .= (count($pros) > 1 ? "and " : "").$pr["title"]; continue; }
                    $course["professions"] .= "{$pr["title"]}, ";
                }

                foreach ($ints_name as $key => $nm) {
                    if(count($ints_name) == $key+1){ $course["instructors"] .= (count($ints_name) > 1 ? "and " : "").$nm["name"]; continue; }
                    $course["instructors"] .= "{$nm["name"]}, ";
                }

                $course['session_end_string'] = date("M. d, Y", strtotime($course['session_end']));
                $course['session_start_string'] = date("M. d, Y", strtotime($course['session_start']));

                $course['discount'] = _get_fast_discount("course", $course["id"]);
                if($discount_vars = $course['discount']){
                    $discount = (($discount_vars->discount/100) * $course['price']);
                    $course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
                }

                /**
                 * coming soon, most popular, and ended
                 * 
                 */
                
                if(in_array($course['fast_cpd_status'], ["cancelled", "ended"])){
                    $course['course_status'] = "Ended";
                    $course['course_status_color'] = "dark";
                }else{
                    if(date("Y-m-d") < date("Y-m-d", strtotime($course['session_start']))){
                        $course['course_status'] = "Coming Soon";
                        $course['course_status_color'] = "warning";
                    }else{
                        $course['course_status'] = "Most Popular";
                        $course['course_status_color'] = "success";
                    }
                }

                $course["total_quizzes"] = _course_total_quizzes($course["id"]);
                $course["total_videos"] = _course_total_videos($course["id"]);

                $course["add_to_cart_button"] = _get_session_cart("course", $course["id"]);
                $course["avg_course_rating"] = _get_avg_rating("course", $course["id"]);

                return $course;
            }, $courses->get()->toArray());
            
            return response()->json(["data"=>$data, "total"=>$total->count()], 200);
        }

        return response()->json([]);
    }

    function top_courses(Request $request) : JsonResponse
    {

        $system_rated = System_Rating_Report::select("data_id as id")->where([
            "type" => "course",
            "week" => date('W'),
            "month" => date('F'),
            "year" => date('Y'),
        ])->get();

        if($system_rated->count() > 0){
            $taking = $request->taking;
            $page = $request->page;
            $offset = $page * $taking;
    
            $courses = Course::select("accreditation", "program_accreditation_no","provider_id", "id", "url", "profession_id", "instructor_id", "price", "course_poster", 
                "title", "headline", "marketing_description","objectives", "requirements", "target_students", "assessment",
                "pass_percentage", "total_unit_amounts", "session_start", "session_end", "language",
                "prc_status", "fast_cpd_status")->where(["prc_status" => "approved"])->whereIn("fast_cpd_status", ["published","live", "ended"])
            ->whereIn("id", $system_rated)->offset($offset)->take($taking)->get();
            
            if($courses->count() > 0){
                $data = array_map(function($course){
                    $course["course_poster"] = _get_image("course", $course["id"])["medium"];
    
                    $pro_ids = $course["profession_id"] ? json_decode($course["profession_id"]) : [];
                    $inst_ids = $course["instructor_id"] ? json_decode($course["instructor_id"]) : [];
        
                    $pros = Profession::select("title")->whereIn("id", $pro_ids)->get()->toArray();
                    $ints = Instructor::select("user_id as id")->whereIn("id", $inst_ids)->get()->toArray();
                    $ints_name = User::select("name")->whereIn("id", $ints)->get()->toArray();
        
                    $course["professions"] = "";
                    $course["instructors"] = "";
        
                    foreach ($pros as $key => $pr) {
                        if(count($pros) == $key+1){ $course["professions"] .= (count($pros) > 1 ? "and " : "").$pr["title"]; continue; }
                        $course["professions"] .= "{$pr["title"]}, ";
                    }
        
                    foreach ($ints_name as $key => $nm) {
                        if(count($ints_name) == $key+1){ $course["instructors"] .= (count($ints_name) > 1 ? "and " : "").$nm["name"]; continue; }
                        $course["instructors"] .= "{$nm["name"]}, ";
                    }
        
                    $course['session_end_string'] = date("M. d, Y", strtotime($course['session_end']));
                    $course['session_start_string'] = date("M. d, Y", strtotime($course['session_start']));

                    $course['discount'] = _get_fast_discount("course", $course["id"]);
                    if($discount_vars = $course['discount']){
                        $discount = (($discount_vars->discount/100) * $course['price']);
                        $course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
                    }
                    
                    /**
                     * coming soon, most popular, and ended
                     * 
                     */
                    
                    if(in_array($course['fast_cpd_status'], ["cancelled", "ended"])){
                        $course['course_status'] = "Ended";
                        $course['course_status_color'] = "dark";
                    }else{
                        if(date("Y-m-d") < date("Y-m-d", strtotime($course['session_start']))){
                            $course['course_status'] = "Coming Soon";
                            $course['course_status_color'] = "warning";
                        }else{
                            $course['course_status'] = "Most Popular";
                            $course['course_status_color'] = "success";
                        }
                    }

                    $course["total_quizzes"] = _course_total_quizzes($course["id"]);
                    $course["total_videos"] = _course_total_videos($course["id"]);

                    $course["add_to_cart_button"] = _get_session_cart("course", $course["id"]);
                    $course["avg_course_rating"] = _get_avg_rating("course", $course["id"]);

                    return $course;
                }, $courses->toArray());
                
                return response()->json(["data"=>$data, "total"=>$total->count()], 200);
            }
        }

        return response()->json([]);
    }

    function newest_courses(Request $request) : JsonResponse
    {
        
        $taking = $request->taking;
        $page = $request->page;
        $offset = $page * $taking;

        $total = Course::select("id")->whereIn("fast_cpd_status", ["published", "live"])->where(["prc_status" => "approved","deleted_at" => null])->take(20)->orderBy('published_at', 'desc')->get();
        $courses = Course::select("accreditation", "program_accreditation_no","provider_id", "id", "url", "profession_id", "instructor_id", "price", "course_poster", 
            "title", "headline", "marketing_description","objectives", "requirements", "target_students", "assessment",
            "pass_percentage", "total_unit_amounts", "session_start", "session_end", "language",
            "prc_status", "fast_cpd_status")->whereIn("fast_cpd_status", ["published", "live"])->where(["prc_status" => "approved","deleted_at" => null])->whereIn("id", $total)
        ->offset($offset)->take($taking)->orderBy('published_at', 'desc');

        $data = array_map(function($course){
            $course["course_poster"] = _get_image("course", $course["id"])["medium"];

            $pro_ids = $course["profession_id"] ? json_decode($course["profession_id"]) : [];
            $inst_ids = $course["instructor_id"] ? json_decode($course["instructor_id"]) : [];

            $pros = Profession::select("title")->whereIn("id", $pro_ids)->get()->toArray();
            $ints = Instructor::select("user_id as id")->whereIn("id", $inst_ids)->get()->toArray();
            $ints_name = User::select("name")->whereIn("id", $ints)->get()->toArray();

            $course["professions"] = "";
            $course["instructors"] = "";

            foreach ($pros as $key => $pr) {
                if(count($pros) == $key+1){ $course["professions"] .= (count($pros) > 1 ? "and " : "").$pr["title"]; continue; }
                $course["professions"] .= "{$pr["title"]}, ";
            }

            foreach ($ints_name as $key => $nm) {
                if(count($ints_name) == $key+1){ $course["instructors"] .= (count($ints_name) > 1 ? "and " : "").$nm["name"]; continue; }
                $course["instructors"] .= "{$nm["name"]}, ";
            }


            $course['session_end_string'] = date("M. d, Y", strtotime($course['session_end']));
            $course['session_start_string'] = date("M. d, Y", strtotime($course['session_start']));
            
            $course['discount'] = _get_fast_discount("course", $course["id"]);
            if($discount_vars = $course['discount']){
                $discount = (($discount_vars->discount/100) * $course['price']);
                $course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
            }
            
            /**
             * coming soon, most popular, and ended
             * 
             */
            
            if(in_array($course['fast_cpd_status'], ["cancelled", "ended"])){
                $course['course_status'] = "Ended";
                $course['course_status_color'] = "dark";
            }else{
                if(date("Y-m-d") < date("Y-m-d", strtotime($course['session_start']))){
                    $course['course_status'] = "Coming Soon";
                    $course['course_status_color'] = "warning";
                }else{
                    $course['course_status'] = "Most Popular";
                    $course['course_status_color'] = "success";
                }
            }
            
            $course["total_quizzes"] = _course_total_quizzes($course["id"]);
            $course["total_videos"] = _course_total_videos($course["id"]);

            $course["add_to_cart_button"] = _get_session_cart("course", $course["id"]);
            $course["avg_course_rating"] = _get_avg_rating("course", $course["id"]);

            return $course;
        }, $courses->get()->toArray());
        
        return response()->json(["data"=>$data, "total"=>$total->count()], 200);
    }

    function provider_courses(Request $request) : JsonResponse
    {
        $provider = $request->provider;
        $course = $request->course;
        $taking = $request->taking;
        $page = $request->page;
        $offset = $page * $taking;

        $total = Course::select("id")->whereIn("fast_cpd_status", ["published", "live"])->where(["prc_status" => "approved","deleted_at" => null, "provider_id"=>$provider])->where("id", "!=", $course)->take(20)->orderBy('published_at', 'desc')->get();
        $courses = Course::select("accreditation", "program_accreditation_no","provider_id", "id", "url", "profession_id", "instructor_id", "price", "course_poster", 
            "title", "headline", "marketing_description","objectives", "requirements", "target_students", "assessment",
            "pass_percentage", "total_unit_amounts", "session_start", "session_end", "language",
            "prc_status", "fast_cpd_status")->whereIn("fast_cpd_status", ["published", "live"])->where(["prc_status" => "approved","deleted_at" => null, "provider_id"=>$provider])->whereIn("id", $total)
        ->offset($offset)->take($taking)->orderBy('published_at', 'desc');

        $data = array_map(function($course){
            $course["course_poster"] = _get_image("course", $course["id"])["medium"];

            $pro_ids = $course["profession_id"] ? json_decode($course["profession_id"]) : [];
            $inst_ids = $course["instructor_id"] ? json_decode($course["instructor_id"]) : [];

            $pros = Profession::select("title")->whereIn("id", $pro_ids)->get()->toArray();
            $ints = Instructor::select("user_id as id")->whereIn("id", $inst_ids)->get()->toArray();
            $ints_name = User::select("name")->whereIn("id", $ints)->get()->toArray();

            $course["professions"] = "";
            $course["instructors"] = "";

            foreach ($pros as $key => $pr) {
                if(count($pros) == $key+1){ $course["professions"] .= (count($pros) > 1 ? "and " : "").$pr["title"]; continue; }
                $course["professions"] .= "{$pr["title"]}, ";
            }

            foreach ($ints_name as $key => $nm) {
                if(count($ints_name) == $key+1){ $course["instructors"] .= (count($ints_name) > 1 ? "and " : "").$nm["name"]; continue; }
                $course["instructors"] .= "{$nm["name"]}, ";
            }

            $course['session_end_string'] = date("M. d, Y", strtotime($course['session_end']));
            $course['session_start_string'] = date("M. d, Y", strtotime($course['session_start']));
            
            $course['discount'] = _get_fast_discount("course", $course["id"]);
            if($discount_vars = $course['discount']){
                $discount = (($discount_vars->discount/100) * $course['price']);
                $course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
            }
            
            /**
             * coming soon, most popular, and ended
             * 
             */
            
            if(in_array($course['fast_cpd_status'], ["cancelled", "ended"])){
                $course['course_status'] = "Ended";
                $course['course_status_color'] = "dark";
            }else{
                if(date("Y-m-d") < date("Y-m-d", strtotime($course['session_start']))){
                    $course['course_status'] = "Coming Soon";
                    $course['course_status_color'] = "warning";
                }else{
                    $course['course_status'] = "Most Popular";
                    $course['course_status_color'] = "success";
                }
            }
            
            $course["total_quizzes"] = _course_total_quizzes($course["id"]);
            $course["total_videos"] = _course_total_videos($course["id"]);

            $course["add_to_cart_button"] = _get_session_cart("course", $course["id"]);
            $course["avg_course_rating"] = _get_avg_rating("course", $course["id"]);

            return $course;
        }, $courses->get()->toArray());
        
        return response()->json(["data"=>$data, "total"=>$total->count()], 200);
    }

    function category(Request $request) : JsonResponse
    {
        $profession = $request->profession;
        $type = $request->type;
        $take = $request->take;
        $page = $request->page;
        $offset = $page * $take;

        switch ($type) {
            case 'popular':
                $total = Course::select("id")->where("published_at", "!=", NULL)->where(["prc_status" => "approved"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->whereIn("fast_cpd_status", ["published", "live", "ended"])->take(20)->orderBy('published_at', 'desc')->get();
                break;
            
            default:
                $total = Course::select("id")->where("published_at", "!=", NULL)->where(["prc_status" => "approved"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->whereIn("fast_cpd_status", ["published", "live", "ended"])->take(4)->orderBy('published_at', 'desc')->get();
                break;
        }
        
        $courses = Course::select("accreditation", "program_accreditation_no","provider_id", "id", "url", "profession_id", "instructor_id", "price", "course_poster", 
            "title", "headline", "marketing_description","objectives", "requirements", "target_students", "assessment",
            "pass_percentage", "total_unit_amounts", "session_start", "session_end", "language",
            "prc_status", "fast_cpd_status")->where("published_at", "!=", NULL)->where(["prc_status" => "approved"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->whereIn("fast_cpd_status", ["published", "live", "ended"])->whereIn("id", $total)
        ->offset($offset)->take($take)->orderBy('published_at', 'desc');

        $data = array_map(function($course){
            $course["course_poster"] = _get_image("course", $course["id"])["medium"];

            $pro_ids = $course["profession_id"] ? json_decode($course["profession_id"]) : [];
            $inst_ids = $course["instructor_id"] ? json_decode($course["instructor_id"]) : [];

            $pros = Profession::select("title")->whereIn("id", $pro_ids)->get()->toArray();
            $ints = Instructor::select("user_id as id")->whereIn("id", $inst_ids)->get()->toArray();
            $ints_name = User::select("name")->whereIn("id", $ints)->get()->toArray();

            $course["professions"] = "";
            $course["instructors"] = "";

            foreach ($pros as $key => $pr) {
                if(count($pros) == $key+1){ $course["professions"] .= (count($pros) > 1 ? "and " : "").$pr["title"]; continue; }
                $course["professions"] .= "{$pr["title"]}, ";
            }

            foreach ($ints_name as $key => $nm) {
                if(count($ints_name) == $key+1){ $course["instructors"] .= (count($ints_name) > 1 ? "and " : "").$nm["name"]; continue; }
                $course["instructors"] .= "{$nm["name"]}, ";
            }

            $course['session_end_string'] = date("M. d, Y", strtotime($course['session_end']));
            $course['session_start_string'] = date("M. d, Y", strtotime($course['session_start']));
            
            $course['discount'] = _get_fast_discount("course", $course["id"]);
            if($discount_vars = $course['discount']){
                $discount = (($discount_vars->discount/100) * $course['price']);
                $course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
            }
            
            /**
             * coming soon, most popular, and ended
             * 
             */
            
            if(in_array($course['fast_cpd_status'], ["cancelled", "ended"])){
                $course['course_status'] = "Ended";
                $course['course_status_color'] = "dark";
            }else{
                if(date("Y-m-d") < date("Y-m-d", strtotime($course['session_start']))){
                    $course['course_status'] = "Coming Soon";
                    $course['course_status_color'] = "warning";
                }else{
                    $course['course_status'] = "Most Popular";
                    $course['course_status_color'] = "success";
                }
            }

            $course["total_quizzes"] = _course_total_quizzes($course["id"]);
            $course["total_videos"] = _course_total_videos($course["id"]);

            $course["add_to_cart_button"] = _get_session_cart("course", $course["id"]);
            $course["avg_course_rating"] = _get_avg_rating("course", $course["id"]);

            return $course;
        }, $courses->get()->toArray());
    
        return response()->json(["data"=>$data, "total"=>$total->count()], 200);
    }

    function in_progress_courses(Request $request) : JsonResponse
    {
        $courses = [];
        if(Auth::check()){
            $progress = Course_Progress::where([
                "user_id" => Auth::user()->id,
                "deleted_at" => null
            ])->get();
    
        }

        return response()->json([]);
    }

    function instructor_list(Request $request) : JsonResponse
    {
        $course = $request->course;
        $take = $request->take;
        $page = $request->page;
        $offset = $page * $take;

        $course = Course::find($course);
        if($course){

            $instructor_ids = $course->instructor_id ? json_decode($course->instructor_id) : [];
            $instructors = User::where([
                "instructor" => "active",
                "deleted_at" => null,
                "status" => "active",
            ])->whereIn("id", $instructor_ids)->offset($offset)->take($take)->orderBy('name', 'asc')->get();

            $instructors = array_map(function($inst){

                $courses = Course::where([
                    "deleted_at" => null,
                    "prc_status" => "approved"
                ])->whereIn("fast_cpd_status", ["published", "live", "ended"])
                ->whereRaw("JSON_CONTAINS(instructor_id, '\"{$inst["id"]}\"')")->get()->count();

                $webinars = Webinar::where([
                    "deleted_at" => null,
                ])->whereIn("fast_cpd_status", ["published", "live", "ended"])
                ->whereRaw("JSON_CONTAINS(instructor_id, '\"{$inst["id"]}\"')")->get()->count();

                $provider = Instructor::where([
                    "user_id" => $inst["id"],
                    "status" => "active"
                ])->get()->count();

                $inst["total_webinars"] = $webinars;
                $inst["total_courses"] = $courses;
                $inst["total_providers"] = $provider;

                return $inst;
            }, $instructors->toArray());
            return response()->json(["data"=>$instructors]);
        }

        return response()->json([]);
    }

    function instructors_courses(Request $request) : JsonResponse
    {
        $course = $request->course;
        $taking = $request->taking;
        $page = $request->page;
        $offset = $page * $taking;

        $current_course = Course::find($course);
        if($current_course){
            
            $course_ids = [];
            foreach(json_decode($current_course->instructor_id) as $instructor){
                $icourses = Course::select("id")->whereIn("fast_cpd_status", ["published", "live"])->where(["prc_status" => "approved","deleted_at" => null])
                ->whereRaw("JSON_CONTAINS(instructor_id, '\"{$instructor}\"')")->take(20)->orderBy('published_at', 'desc')->where("id", "!=", $course)->get();
                
                if($icourses->count() > 0){
                    $course_ids = array_merge($course_ids, $icourses->toArray());
                }
            }
            $total = collect($course_ids)->unique()->take(20);
            $courses = Course::select("accreditation", "program_accreditation_no","program_accreditation_no","provider_id", "id", "url", "profession_id", "instructor_id", "price", "course_poster", 
                "title", "headline", "marketing_description","objectives", "requirements", "target_students", "assessment",
                "pass_percentage", "total_unit_amounts", "session_start", "session_end", "language",
                "prc_status", "fast_cpd_status")->whereIn("fast_cpd_status", ["published", "live"])->where(["prc_status" => "approved","deleted_at" => null])->whereIn("id", $total)
            ->offset($offset)->take($taking)->orderBy('published_at', 'desc');
    
            $data = array_map(function($course){
                $course["course_poster"] = _get_image("course", $course["id"])["medium"];

                $pro_ids = $course["profession_id"] ? json_decode($course["profession_id"]) : [];
                $inst_ids = $course["instructor_id"] ? json_decode($course["instructor_id"]) : [];
    
                $pros = Profession::select("title")->whereIn("id", $pro_ids)->get()->toArray();
                $ints = Instructor::select("user_id as id")->whereIn("id", $inst_ids)->get()->toArray();
                $ints_name = User::select("name")->whereIn("id", $ints)->get()->toArray();
    
                $course["professions"] = "";
                $course["instructors"] = "";
    
                foreach ($pros as $key => $pr) {
                    if(count($pros) == $key+1){ $course["professions"] .= (count($pros) > 1 ? "and " : "").$pr["title"]; continue; }
                    $course["professions"] .= "{$pr["title"]}, ";
                }
    
                foreach ($ints_name as $key => $nm) {
                    if(count($ints_name) == $key+1){ $course["instructors"] .= (count($ints_name) > 1 ? "and " : "").$nm["name"]; continue; }
                    $course["instructors"] .= "{$nm["name"]}, ";
                }
    
                $course['session_end_string'] = date("M. d, Y", strtotime($course['session_end']));
                $course['session_start_string'] = date("M. d, Y", strtotime($course['session_start']));
                
                $course['discount'] = _get_fast_discount("course", $course["id"]);
                if($discount_vars = $course['discount']){
                    $discount = (($discount_vars->discount/100) * $course['price']);
                    $course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
                }

                /**
                 * coming soon, most popular, and ended
                 * 
                 */
                
                if(in_array($course['fast_cpd_status'], ["cancelled", "ended"])){
                    $course['course_status'] = "Ended";
                    $course['course_status_color'] = "dark";
                }else{
                    if(date("Y-m-d") < date("Y-m-d", strtotime($course['session_start']))){
                        $course['course_status'] = "Coming Soon";
                        $course['course_status_color'] = "warning";
                    }else{
                        $course['course_status'] = "Most Popular";
                        $course['course_status_color'] = "success";
                    }
                }
                
                $course["total_quizzes"] = _course_total_quizzes($course["id"]);
                $course["total_videos"] = _course_total_videos($course["id"]);
    
                $course["add_to_cart_button"] = _get_session_cart("course", $course["id"]);
                $course["avg_course_rating"] = _get_avg_rating("course", $course["id"]);

                return $course;
            }, $courses->get()->toArray());
            
            return response()->json(["data"=>$data, "total"=>$total->count()], 200);
        }

        return response()->json([]);
    }

    function request_course_preview(Request $request) : JsonResponse
    {
        $course = $request->course;

        $send = Course::find($course);
        if($send){
            if($send->course_video){

                $explode_url = explode("/", $send->course_video);
                $filename = end($explode_url);
                $expload_filename = explode(".", $filename);
                $extension = end($expload_filename);

                $extension = strtolower($extension) == 'mov' ? 'mp4' : strtolower($extension);
                return response()->json(["poster"=>$send->course_poster, "video"=>$send->course_video, "extension"=>$extension]);
            }
        }

        return response()->json([], 422);
    }

    function view_units(Request $request) : JsonResponse
    {
        if($request->item_id){
            $pritem = Purchase_Item::select("credited_cpd_units")->find($request->item_id);
            if($pritem){
                return response()->json(json_decode($pritem->credited_cpd_units), 200);
            }
        }

        return response()->json([], 422);
    }
}

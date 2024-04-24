<?php

namespace App\Http\Controllers\Api;

use App\{User, Provider,
    Instructor, Course, Profession
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;

use DateTime;
use Response;
use Session;

class ProfileController extends Controller
{
    function user_assoc_providers(Request $request) : JsonResponse
    {
        $take = $request->take;
        $page = $request->page;
        $offset = $page * $take;

        $providers = Instructor::select("provider_id as id")->where([
            "user_id" => Auth::user()->id,
            "status" => "active",
            "deleted_at" => null,
        ])->offset($offset)->take($take)->get();

        if($providers->count() > 0){
            $total_count = Instructor::select("provider_id")->where([
                "user_id" => Auth::user()->id,
                "status" => "active",
                "deleted_at" => null,
            ])->get()->count();

            $providers = array_map(function($provider){
                $courses = Course::where([
                    "deleted_at" => null,
                    "prc_status" => "approved",
                    "provider_id" => $provider["id"],
                ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get()->count();

                $info = Provider::find($provider["id"]);

                $provider["total_courses"] = $courses;
                $provider["info"] = $info;

                return $provider;
            }, $providers->toArray());
            return response()->json(["data"=>$providers, "total"=>$total_count]);
        }

        return response()->json([]);
    }

    function user_assoc_courses(Request $request) : JsonResponse
    {
        $take = $request->take;
        $page = $request->page;
        $offset = $page * $take;

        $total = Course::select("id")->where("published_at", "!=", NULL)->where(["prc_status" => "approved"])->whereIn("fast_cpd_status", ["published", "live", "ended"])->whereRaw("JSON_CONTAINS(instructor_id, '\"".Auth::user()->id."\"')")->orderBy('published_at', 'desc')->get();
        $courses = Course::select("accreditation", "program_accreditation_no","provider_id", "id", "url", "profession_id", "instructor_id", "price", "course_poster", 
            "title", "headline", "marketing_description","objectives", "requirements", "target_students", "assessment",
            "pass_percentage", "total_unit_amounts", "session_start", "session_end", "language",
            "prc_status", "fast_cpd_status")->whereIn("id", $total)->offset($offset)->take($take)->orderBy('published_at', 'desc');

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
}

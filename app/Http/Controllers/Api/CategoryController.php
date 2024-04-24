<?php

namespace App\Http\Controllers\Api;

use App\{
    User, Profession, Provider, Instructor, Course, Co_Provider, 
    System_Rating_Report, Course_Rating,
    Course_Progress, 
    Webinar,
    Webinar_Series, Webinar_Session,

    Quiz, Quiz_Item, Video, Article,
    Section,

    My_Cart, 
    Purchase, Purchase_Item
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Support\Collection;

use DateTime;
use Response; 
use Session; 

class CategoryController extends Controller
{
    function providers(Request $request) : JsonResponse
    {   
        $profession = $request->profession;
        $type = $request->type;
        $take = $request->take;
        $page = $request->page;
        $offset = $page * $take;

        $total_courses = Course::select("provider_id as id")->where("published_at", "!=", NULL)->where(["prc_status" => "approved"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->whereIn("fast_cpd_status", ["published", "live", "ended"])->take(20)->groupBy('provider_id')->get();
        $total_webinars = Webinar::select("provider_id as id")->where("published_at", "!=", NULL)->where(["prc_status" => "approved"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->whereIn("fast_cpd_status", ["published", "live", "ended"])->take(20)->groupBy('provider_id')->get();

        $total_ids = array_merge($total_courses->toArray(), $total_webinars->toArray());
        $providers = Provider::whereIn("id", $total_ids)->get();
        $providers = array_map(function($pr) use($profession){
            $course_ids = Course::select("id")->where("provider_id", "=", $pr["id"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->get();
            $webinar_ids = Webinar::select("id")->where("provider_id", "=", $pr["id"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->get();

            $pr["total_courses"] = $course_ids->count();
            $pr["total_webinars"] = $webinar_ids->count();

            return $pr;
        }, $providers->toArray());

        return response()->json(["data"=>$providers, "total"=> count($total_ids)], 200);
    }

    function webinars(Request $request) : JsonResponse
    {
        $profession = $request->profession;
        $taking = $request->take;
        $page = $request->page;
        $offset = $page * $taking;

        $total = Webinar::select("id")->where(["deleted_at" => null, "fast_cpd_status" => "published"])->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->take(20)->orderBy('published_at', 'desc')->get();
        $webinars = Webinar::select(
            "id", "url", "title", "profession_id", "instructor_id",
            "headline", "description", "offering_units", "event",
            "prices",
            "webinar_poster_id", "webinar_poster", "webinar_video",
            "objectives", "accreditation", "fast_cpd_status", "type",
            "target_number_students"
        )->whereIn("fast_cpd_status", ["published", "live"])->where(["deleted_at" => null, "fast_cpd_status" => "published"])
        ->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"' )")->whereIn("id", $total)
        ->offset($offset)->take($taking)->orderBy('published_at', 'desc')->get();

        $webinar_collection = [];
        foreach ($webinars->toArray() as $webinar) {
            $webinar["webinar_poster"] = _get_image("webinar", $webinar["id"])["small"];

            $pro_ids = $webinar["profession_id"] ? json_decode($webinar["profession_id"]) : [];
            $inst_ids = $webinar["instructor_id"] ? json_decode($webinar["instructor_id"]) : [];

            $pros = Profession::select("title")->whereIn("id", $pro_ids)->get()->toArray();
            $ints = Instructor::select("user_id as id")->whereIn("id", $inst_ids)->get()->toArray();
            $ints_name = User::select("name")->whereIn("id", $ints)->get()->toArray();

            $webinar["professions"] = "";
            $webinar["instructors"] = "";

            foreach ($pros as $key => $pr) {
                if(count($pros) == $key+1){ $webinar["professions"] .= (count($pros) > 1 ? "and " : "").$pr["title"]; continue; }
                $webinar["professions"] .= "{$pr["title"]}, ";
            }

            foreach ($ints_name as $key => $nm) {
                if(count($ints_name) == $key+1){ $webinar["instructors"] .= (count($ints_name) > 1 ? "and " : "").$nm["name"]; continue; }
                $webinar["instructors"] .= "{$nm["name"]}, ";
            }

            /**
             * get before date
             */
            $webinar = $this->webinar_schedule($webinar);
            $webinar["total_quiz"] = _webinar_total_quizzes($webinar["id"]);
            $webinar["total_article"] = _webinar_total_article($webinar["id"]);
            $webinar["accreditation"] = json_decode($webinar["accreditation"]);
            $webinar["prices"] = json_decode($webinar["prices"]);

            if($webinar["offering_units"]=="without"){
                if(($price = $webinar["prices"]->without) > 0){
                    $webinar['discount'] = _get_fast_discount("webinar", $webinar["id"]);
                    if($webinar['discount']){
                        $discount_value = (($webinar['discount']->discount/100) * $price);
                        $webinar['discounted_price'] = number_format($price - $discount_value, 2, '.', ',');
                        $webinar['price'] = $price;
                    }
                }
            }else{
                if(($price = $webinar["prices"]->with) > 0){
                    $webinar['discount'] = _get_fast_discount("webinar", $webinar["id"]);
                    if($webinar['discount']){
                        $discount_value = (($webinar['discount']->discount/100) * $price);
                        $webinar['discounted_price'] = number_format($price - $discount_value, 2, '.', ',');
                        $webinar['price'] = $price;
                    }
                }
            }

            $webinar_collection[] = $webinar;
        }
        
        return response()->json(["data"=>$webinar_collection, "total"=>$total->count()], 200);
    }

    function webinar_schedule($webinar){
        $sessions = Webinar_Session::whereDate("session_date", ">=", date("Y-m-d"))
        ->where([ "webinar_id" => $webinar["id"], "deleted_at" => null ])->get();

        if($sessions->count() > 0){
            if(in_array($webinar['fast_cpd_status'], ["cancelled", "ended"])){
                $webinar["webinar_purchase_before"] = [
                    "status" => "Ended",
                    "color" => "dark",
                ];
            }else{
                $sessions = $sessions->toArray();
                $first_session = $sessions[0];
                if($first_session["session_date"] == date("Y-m-d")){
                    $webinar["webinar_purchase_before"] = [
                        "status" => "Live Today",
                        "color" => "success",
                    ];
                }else{
                    $today = new DateTime();
                    $next = new DateTime($first_session["session_date"]);
                    $diff = $next->diff($today)->format("%a");
                    $webinar["webinar_purchase_before"] = [
                        "status" => $diff > 0 ? "Live in ".($diff+1)." days" : "Live Tomorrow",
                        "color" => "success",
                        "string_" => date("M. d, Y", strtotime($first_session["session_date"])),
                        "date_" => $first_session["session_date"],
                    ];
                }
            }
        }else{
            $webinar["webinar_purchase_before"] = [
                "status" => "Ended",
                "color" => "dark",
            ];
        }

        return $webinar;
    }

    function list(Request $request) : JsonResponse
    {
        $profession = $request->profession;
        $filter = $request->filter;
        $type = $request->type;
        $take = $request->pagination['take'];
        $page = $request->pagination['page'];
        $order = $request->pagination['order'];
        $offset = $page * $take;

        $total_courses = Course::select("id")->where(["prc_status" => "approved"])->whereIn("fast_cpd_status", ["published", "live", "ended"])
        ->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"')");

        $total_webinars = Webinar::select("id")->where(["prc_status" => "approved"])->whereIn("fast_cpd_status", ["published", "live", "ended"])
        ->whereRaw("JSON_CONTAINS(profession_id, '\"{$profession}\"')");

        // filter language
        if (array_key_exists("language", $filter)) {
            $total_courses = $total_courses->whereIn("language", $filter["language"]);
            $total_webinars = $total_webinars->whereIn("language", $filter["language"]);
        }

        $total_courses = $total_courses->get()->toArray();
        $total_webinars = $total_webinars->get()->toArray();

        // filter quiz
        switch ($filter["quiz"]) {
            case "0": // no-quiz
                $total_courses = array_filter($total_courses, function($course){
                    $sections = Section::select("id as section_id")->where([
                        "type"=>"course", "data_id"=> $course["id"],
                        "deleted_at" => null,
                    ])->get();

                    $quizzess = Quiz::whereIn("section_id", $sections)->where("deleted_at", "=", null)->get()->count();
                    if($quizzess > 0){
                    }else{
                        return $course;
                    }
                });

                $total_webinars = array_filter($total_webinars, function($webinar){
                    $sections = Section::select("id as section_id")->where([
                        "type"=>"webinar", "data_id"=> $webinar["id"],
                        "deleted_at" => null,
                    ])->get();

                    $quizzess = Quiz::whereIn("section_id", $sections)->where("deleted_at", "=", null)->get()->count();
                    if($quizzess > 0){
                    }else{
                        return $webinar;
                    }
                });
                break;

            case "1": // with-quiz
                $total_courses = array_filter($total_courses, function($course){
                    $sections = Section::select("id as section_id")->where([
                        "type"=>"course", "data_id"=> $course["id"],
                        "deleted_at" => null,
                    ])->get();

                    $quizzess = Quiz::whereIn("section_id", $sections)->where("deleted_at", "=", null)->get()->count();
                    if($quizzess > 0){
                        return $course;
                    }
                });

                $total_webinars = array_filter($total_webinars, function($webinar){
                    $sections = Section::select("id as section_id")->where([
                        "type"=>"webinar", "data_id"=> $webinar["id"],
                        "deleted_at" => null,
                    ])->get();

                    $quizzess = Quiz::whereIn("section_id", $sections)->where("deleted_at", "=", null)->get()->count();
                    if($quizzess > 0){
                        return $webinar;
                    }
                });
                break;
            
            default: // all
                
                break;
        }

        $courses = Course::select(
            "accreditation", "provider_id", "id", "url", "price", 
            "course_poster", "course_poster_id", 
            "title", "headline",
            "language", "prc_status", "fast_cpd_status", "published_at"
        )->where("published_at", "!=", NULL)
        ->where(["prc_status" => "approved"])
        ->whereIn("fast_cpd_status", ["published", "live", "ended"])
        ->whereIn("id", $total_courses)
        ->whereRaw("JSON_CONTAINS(`profession_id`, '\"{$profession}\"' )")
        ->offset($offset)->take($take); 
        
        $webinars = Webinar::select(
            "accreditation", "provider_id", "id", "url", 
            "offering_units", "event", "prices", 
            "webinar_poster", "webinar_poster_id", 
            "title", "headline",
            "language", "prc_status", "fast_cpd_status", "type as webinar_type",
            "published_at"
        )->where("published_at", "!=", NULL)
        ->where(["prc_status" => "approved"])
        ->whereIn("fast_cpd_status", ["published", "live", "ended"])
        ->whereIn("id", $total_webinars)
        ->whereRaw("JSON_CONTAINS(`profession_id`, '\"{$profession}\"' )")
        ->offset($offset)->take($take); 

        $data = collect();

        $courses = $courses->get();
        foreach ($courses->toArray() as $course) {
            $course["poster"] = _get_image("course", $course["id"])["small"];
            $course["discount"] = _get_fast_discount("course", $course["id"]);
            if($course["discount"]){
                $discount_value = ($course["discount"]["discount"]/100) * $course["price"];
                $course["discounted_price"] = $course["price"] - $discount_value;
            }
            $course["provider"] = Provider::select("name", "url", "id")->find($course["provider_id"]);
            $course["type"] = "course"; 

            $course["highest_price"] = (float)$course["price"];
            $course["lowest_price"] = (float)$course["price"];

            $accreditation = json_decode($course["accreditation"]);
            $course["accreditation"] = $accreditation;
            $course["highest_unit"] = (float)max(array_column($accreditation, "units"));
            $course["lowest_unit"] = (float)min(array_column($accreditation, "units"));

            $data[] = $course;
        }

        $webinars = $webinars->get();
        foreach ($webinars->toArray() as $webinar) {
            $webinar["poster"] = _get_image("webinar", $webinar["id"])["small"];
            $webinar["discount"] = _get_fast_discount("webinar", $webinar["id"]);

            $webinar["provider"] = Provider::select("name", "url", "id")->find($webinar["provider_id"]);
            $webinar["type"] = "webinar";

            $webinar["highest_price"] = 0;
            $webinar["lowest_price"] = 0;
            $webinar["highest_unit"] = 0;
            $webinar["lowest_unit"] = 0;
            
            $prices = json_decode($webinar["prices"]);
            $webinar["prices"] = $prices;
            if($webinar["offering_units"] == "both"){
                $webinar["highest_price"] = (float)$prices->with > $prices->without ? $prices->with : $prices->without;
                $webinar["lowest_price"] = (float)$prices->with < $prices->without ? $prices->with : $prices->without;
            }else if($webinar["offering_units"] == "with"){
                $webinar["highest_price"] = (float)$prices->with;
                $webinar["lowest_price"] = (float)$prices->with;
            }else{
                $webinar["highest_price"] = (float)$prices->without;
                $webinar["lowest_price"] = (float)$prices->without;
            }
            
            if($webinar["discount"]){
                $discount_value = ($webinar["discount"]["discount"]/100) * $webinar["highest_price"];
                $webinar["discounted_price"] = ($webinar["highest_price"] > 0 ? $webinar["highest_price"] - $discount_value : 0);
            }

            if($webinar["accreditation"]){
                $accreditation = json_decode($webinar["accreditation"]);
                $webinar["accreditation"] = $accreditation;
                $webinar["highest_unit"] = (float)max(array_column($accreditation, "units"));
                $webinar["lowest_unit"] = (float)min(array_column($accreditation, "units"));
            }

            $data[] = $webinar;
        }
    
        // order by
        switch ($order) {
            case 'popularity':
                # code...
                break;

            case 'newest':
                $data = $data->sortByDesc("published_at")->values();
                return response()->json(["data"=>$data, "total"=>count($total_courses) + count($total_webinars)], 200);
            break;

            case 'highest price':
                $data = $data->sortByDesc("highest_price")->values();
                return response()->json(["data"=>$data, "total"=>count($total_courses) + count($total_webinars)], 200);
            break;

            case 'lowest price':
                $data = $data->sortBy("lowest_price")->values();
                return response()->json(["data"=>$data, "total"=>count($total_courses) + count($total_webinars)], 200);
            break;

            case 'highest unit':
                $data = $data->sortByDesc("highest_unit")->values();
                return response()->json(["data"=>$data, "total"=>count($total_courses) + count($total_webinars)], 200);
            break;

            case 'lowest unit':
                $data = $data->sortBy("lowest_unit")->values();
                return response()->json(["data"=>$data, "total"=>count($total_courses) + count($total_webinars)], 200);
            break;
        }

        return response()->json(["data"=>$data, "total"=>count($total_courses) + count($total_webinars)], 200);
    }
}
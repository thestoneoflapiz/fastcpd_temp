<?php

namespace App\Http\Controllers\Api;

use App\{
    User, Profession, Provider, Instructor, Co_Provider, 
    Webinar, Webinar_Series, Webinar_Session,
    Section, Quiz, Quiz_Item, Video, Article,

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

class WebinarController extends Controller
{
    function newest_webinars(Request $request) : JsonResponse
    {
        $taking = $request->taking;
        $page = $request->page;
        $offset = $page * $taking;

        $webinars = Webinar::select(
            "id", "url", "title", "profession_id", "instructor_id",
            "headline", "description", "offering_units", "event",
            "prices",
            "webinar_poster_id", "webinar_poster", "webinar_video",
            "objectives", "accreditation", "fast_cpd_status", "type",
            "target_number_students"
        )->where(["deleted_at" => null])->whereIn("fast_cpd_status", ["published", "live"]);

        $total = $webinars->orderBy('published_at', 'desc')->get();
        $webinars = $webinars->offset($offset)->take($taking)->orderBy('published_at', 'desc')->get();

        $webinar_collection = [];
        foreach ($webinars->toArray() as $webinar) {
            $webinar = $this->webinar_schedule($webinar);
            if(!$webinar){
                continue;
            }

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

            $webinar["total_quiz"] = _webinar_total_quizzes($webinar["id"]);
            $webinar["total_article"] = _webinar_total_article($webinar["id"]);
            $webinar["accreditation"] = $webinar["accreditation"] ? json_decode($webinar["accreditation"]) : $webinar["accreditation"];
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

            $webinar["add_to_cart_button"] = _get_session_cart("webinar", $webinar["id"]);
            $webinar["avg_webinar_rating"] = _get_avg_rating("webinar", $webinar["id"]);

            $webinar_collection[] = $webinar;
        }

        return response()->json(["data"=>$webinar_collection, "total"=>$total->count()], 200);
    }

    function provider_webinars(Request $request) : JsonResponse
    {
        $provider = $request->provider;
        $webinar = $request->webinar;
        $taking = $request->taking;
        $page = $request->page;
        $offset = $page * $taking;

        if($provider){
            $total = Webinar::select("id")->whereIn("fast_cpd_status", ["published", "live"])->where(["deleted_at" => null, "provider_id"=>$provider])->where("id", "!=", $webinar)->take(20)->orderBy('published_at', 'desc')->get();
            $webinars = Webinar::select(
                "id", "url", "title", "profession_id", "instructor_id",
                "headline", "description", "offering_units", "event",
                "prices",
                "webinar_poster_id", "webinar_poster", "webinar_video",
                "objectives", "accreditation", "fast_cpd_status", "type",
                "target_number_students"
            )->whereIn("fast_cpd_status", ["published", "live"])->where(["deleted_at" => null, "provider_id"=>$provider])->whereIn("id", $total)
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

        return response()->json([]);
    }

    function webinar_schedule($webinar)
    {
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

    function instructor_list(Request $request) : JsonResponse
    {
        $webinar = $request->webinar;
        $take = $request->take;
        $page = $request->page;
        $offset = $page * $take;

        $webinar = Webinar::find($webinar);
        if($webinar){

            $instructor_ids = $webinar->instructor_id ? json_decode($webinar->instructor_id) : [];
            $instructors = User::where([
                "instructor" => "active",
                "deleted_at" => null,
                "status" => "active",
            ])->whereIn("id", $instructor_ids)->offset($offset)->take($take)->orderBy('name', 'asc')->get();

            $instructors = array_map(function($inst){

                $webinars = Webinar::where([
                    "deleted_at" => null,
                ])->whereIn("fast_cpd_status", ["published", "live", "ended"])
                ->whereRaw("JSON_CONTAINS(instructor_id, '\"{$inst["id"]}\"')")->get()->count();

                $provider = Instructor::where([
                    "user_id" => $inst["id"],
                    "status" => "active"
                ])->get()->count();

                $inst["total_webinars"] = $webinars;
                $inst["total_providers"] = $provider;

                return $inst;
            }, $instructors->toArray());
            return response()->json(["data"=>$instructors]);
        }

        return response()->json([]);
    }
    
    function check_slots(Request $request) : JsonResponse
    {
        $free_slots = true;
        $remaining = 0;
        $webinar = Webinar::select("id", "event", "target_number_students as limit")
            ->find($request->webinar_id);
        if($webinar && $webinar->limit > 0){
            if($webinar->event == "day"){

                $session = Webinar_Session::select("id as schedule_id")->where([
                    "webinar_id" => $webinar->id,
                    "id" => $request->id,
                ])->first();

                if($session){
                    $exising_purchase = Purchase_Item::select("id")->where([
                        "type" => "webinar",
                        "data_id" => $webinar->id,
                        "schedule_id" => $session->schedule_id,
                        "schedule_type" => $request->event,
                    ])->whereIn("payment_status", [
                        "paid", "waiting", "pending"
                    ])->get();
    
                    if($exising_purchase && ($exising_purchase->count() >= $webinar->limit)){
                        $free_slots = false;
                    }else{
                        if($exising_purchase->count() <= $webinar->limit){
                            $remaining = $webinar->limit - $exising_purchase->count();
                        }
                    }
                }

            }else{
                $series = Webinar_Series::select("id as schedule_id")->where([
                    "webinar_id" => $webinar->id,
                    "id" => $request->id,
                ])->first();

                if($series){
                    $exising_purchase = Purchase_Item::select("id")->where([
                        "type" => "webinar",
                        "data_id" => $webinar->id,
                        "schedule_id" => $series->schedule_id,
                        "schedule_type" => $request->event,
                    ])->whereIn("payment_status", [
                        "paid", "waiting", "pending"
                    ])->get();
    
                    if($exising_purchase && ($exising_purchase->count() >= $webinar->limit)){
                        $free_slots = false;
                    }else{
                        if($exising_purchase->count() <= $webinar->limit){
                            $remaining = $webinar->limit - $exising_purchase->count();
                        }
                    }
                }
            }
        }

        return response()->json([
            "slots" => $free_slots,
            "remaining" =>  $remaining,
        ]);
    }
}
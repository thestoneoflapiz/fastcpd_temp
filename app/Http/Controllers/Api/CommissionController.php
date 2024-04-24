<?php

namespace App\Http\Controllers\Api;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Course, Instructor_Permissions, Image_Intervention,
    Webinar,Purchase_Item
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class CommissionController extends Controller
{

    function list(Request $request) : JsonResponse
    {   
        $voucher = _get_promoter_voucher(Auth::guard('promoters')->user()->id);
        $request = $request->all();
        $query = $request['query'];
        $paramFilter = Session::get("commission_query");
        $from = date("Y-m-d", strtotime($paramFilter["created_at"]['from']['values'][0]));
        $to = date("Y-m-d", strtotime($paramFilter["created_at"]['to']['values'][0]));
        if($paramFilter){
            $purchases = Purchase_Item::where("payment_status","paid")
            ->whereDate('created_at',">=",$from)
            ->whereDate("created_at","<=", $to)
            ->where("voucher",$voucher->voucher_code)
            ->where("channel","promoter_promo");
            
        }else{
            $purchases = Purchase_Item::where("payment_status","paid")
            ->where("voucher",$voucher->voucher_code)
            ->where("channel","promoter_promo");
        }
        
        $pagination = $request['pagination'];
        $offset = $pagination['page'] == 1 ? 0 : ($pagination['page'] - 1) * $pagination['perpage'];
        $sort = $request['sort'] ?? null;


        // if($query['generalSearch']){
        //     $purchases = $purchases->whereRaw("`type` LIKE '%{$query['generalSearch']}%' OR `price` LIKE '%{$query['generalSearch']}%' OR `promoter_revenue` LIKE '%{$query['generalSearch']}%'");
        // }
        if($sort){
            $purchases = $purchases->take($pagination['perpage'])->offset($offset)->orderBy($sort["field"], $sort["sort"])->get();
        }else{
            $purchases = $purchases->take($pagination['perpage'])->offset($offset)->get();
        }
        

        return response()->json(["data"=>$purchases, "total"=>$purchases->count()], 200);
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
    function query(Request $request){
        Session::put("commission_query", $request->all());
    }
}

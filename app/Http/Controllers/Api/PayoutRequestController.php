<?php

namespace App\Http\Controllers\Api;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Course, Instructor_Permissions, Image_Intervention,
    Webinar,Purchase_Item,Payout
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Carbon\Carbon;
use Response; 
use Session;

class PayoutRequestController extends Controller
{

    function list(Request $request) : JsonResponse
    {   
        // $voucher = _get_promoter_voucher(Auth::guard('promoters')->user()->id);
        $request = $request->all();
        $query = $request['query'];
        $paramFilter = Session::get("payout_query");
        $from = date("Y-m-d", strtotime($paramFilter["created_at"]['from']['values'][0]));
        $to = date("Y-m-d", strtotime($paramFilter["created_at"]['to']['values'][0]));
        if($paramFilter){

            $payouts = Payout::where("data_id",Auth::guard('promoters')->user()->id)
            ->whereDate('created_at',">=",$from)
            ->whereDate("created_at","<=", $to)
            ->where("type","promoter");
            
        }else{
            $payouts = Payout::
            // where("payment_status","paid")
            where("data_id",Auth::guard('promoters')->user()->id)
            ->where("type","promoter");
        }

        
        $pagination = $request['pagination'];
        $offset = $pagination['page'] == 1 ? 0 : ($pagination['page'] - 1) * $pagination['perpage'];
        


        // if($query['generalSearch']){
        //     $purchases = $purchases->whereRaw("`type` LIKE '%{$query['generalSearch']}%' OR `price` LIKE '%{$query['generalSearch']}%' OR `promoter_revenue` LIKE '%{$query['generalSearch']}%'");
        // }
        if(isset($request['sort'])){
            $sort = $request['sort'];
            $payouts = $payouts->take($pagination['perpage'])->offset($offset)->orderBy($sort["field"], $sort["sort"])->get();
        }else{
            $payouts = $payouts->take($pagination['perpage'])->offset($offset)->get();
        }
        

        return response()->json(["data"=>$payouts, "total"=>$payouts->count()], 200);
    }


    function query(Request $request){
        Session::put("payout_query", $request->all());
    }

    function confirm_payout(Request $request){
        // dd($request->payment_method);
        $id = Auth::guard('promoters')->user()->id;
        $revenue_info = _get_balance($id);

        $existing_payouts = Payout::where("data_id",$id)
                            ->where("type","promoter")
                            ->latest('created_at')
                            ->first();
        if($existing_payouts){
            $payout = new Payout;
            $payout->type = "promoter";
            $payout->data_id = $id;
            $payout->date_from = $existing_payouts->created_at;
            $payout->date_to = date("Y-m-d H:i:s");
            $payout->price_paid = $revenue_info["collected"] ;
            $payout->fast_revenue = $revenue_info["fast_revenue"];
            $payout->provider_revenue = $revenue_info["provider_revenue"];
            $payout->promoter_revenue = $revenue_info["balance"];
            $payout->created_at =  date("Y-m-d H:i:s");
            $payout->updated_at = date("Y-m-d H:i:s");
            $payout->status = "waiting";
            $payout->payment_method = $request->payment_method;
            $payout->save();
        }else{

            $first_purchase = Purchase_Item::select("purchases.updated_at","purchases.id")
            ->where("purchases.payment_status","paid")
            ->where("purchase_items.voucher",_get_promoter_voucher($id)->voucher_code)
            ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
            ->orderBy("purchases.updated_at", "asc")
            ->first();

            $payout = new Payout;
            $payout->type = "promoter";
            $payout->data_id = $id;
            $payout->date_from = $first_purchase->updated_at;
            $payout->date_to = date("Y-m-d H:i:s");
            $payout->price_paid = $revenue_info["collected"] ;
            $payout->fast_revenue = $revenue_info["fast_revenue"];
            $payout->provider_revenue = $revenue_info["provider_revenue"];
            $payout->promoter_revenue = $revenue_info["balance"];
            $payout->created_at =  date("Y-m-d H:i:s");
            $payout->updated_at = date("Y-m-d H:i:s");
            $payout->status = "waiting";
            $payout->payment_method = $request->payment_method;
            $payout->save();
            
        }
        
        // Session::put("payout_query", $request->all());
    }
}

<?php

namespace App\Http\Controllers\Superadmin;

use App\{User, Provider, Co_Provider, Instructor, Course,Purchase, Purchase_Item};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class PurchaseController extends Controller
{
    function report_purchase_list(Request $request) : JsonResponse
    {

        $start_date = date("Y-m-d",strtotime($request->start_date));
        $end_date = date("Y-m-d",strtotime($request->end_date));
        $method = $request->method;
       

        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
      
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ? ($paramTable["pagination"]["perpage"] <= 0 ? 10 : $paramTable["pagination"]["perpage"]) : '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
        if($start_date == null || $end_date == null || $method == null){
            return response()->json(["data"=>[]],200);
        }


        if($method[0] == 0){
            $purchases = Purchase::select(
                "purchases.id as purchase_id","purchases.reference_number as transaction_code","purchases.total_amount as purchased_price",
                "purchases.created_at as purchased_date","purchases.updated_at as payment_date",
                "purchases.total_discount as discount","purchases.payment_method as payment_method","users.name as purchased_by",
                "purchases.payment_gateway","purchases.payment_method","purchases.total_discount"
            )
            ->whereDate("purchases.updated_at",">=",$start_date)->whereDate("purchases.updated_at","<=",$end_date)
            ->where("purchases.payment_status","paid")
            ->leftJoin("users","users.id","purchases.user_id");
        }else{
            $methods = array();
            foreach($method as $payment_method)
            {
                if($payment_method == "1"){
                    $arr = array("card");
                   $methods = array_merge($methods,$arr);
                }
                if($payment_method == "2"){
                    $arr = array("gcash","grab_pay");
                    $methods = array_merge($methods,$arr);
                }
                if($payment_method == "3"){
                    $arr = array("bdo","bpib","cbc","lbpa","mayb","mbtc","psb","rcbc","rsb","ubpb","ucpb");
                    $methods = array_merge($methods,$arr);
                }
                if($payment_method == "4"){
                    $arr = array("aub","bdrx","cbcx","ewxb","lbxb","mbxb","pnxb","sbcb","rcxb","rsbb","ubxb","ucxb");
                    $methods = array_merge($methods,$arr);
                }
                if($payment_method == "5"){
                    $arr = array("smr","bayd","cebl","mlh");
                    $methods = array_merge($methods,$arr);
                }
            }

            $purchases = Purchase::select(
                "purchases.id as purchase_id","purchases.reference_number as transaction_code","purchases.total_amount as purchased_price",
                "purchases.created_at as purchased_date","purchases.updated_at as payment_date",
                "purchases.total_discount as discount","purchases.payment_method as payment_method","users.name as purchased_by",
                "purchases.payment_gateway", "purchases.payment_method","purchases.total_discount"
            )
            ->whereDate("purchases.updated_at",">=",$start_date)->whereDate("purchases.updated_at","<=",$end_date)
            ->whereIn("purchases.payment_method",$methods)
            ->where("purchases.payment_status","paid")
            ->leftJoin("users","users.id","purchases.user_id");
        }
     

        $quotient = floor($purchases->count() / $perPage);
        $reminder = ($purchases->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $purchases->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "transaction_code",
        );
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["transaction_code", "purchased_by", "purchased_date","payment_date","purchased_price","payment_method"]) ? $paramTable["sort"]["field"] : "transaction_code";

            $purchases = $purchases->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                            ->skip($offset)->take($perPage);          
        }else{
            $purchases = $purchases->skip($offset)->take(10);
        }

        $purchases_ = $purchases->get();

        $data = array_map(function($purchase){

            $purchase_items = Purchase_Item::select(DB::raw("sum(price) as original_price"),DB::raw("count(id) as items"))->where("purchase_id",$purchase['purchase_id'])->first();
  
            return[
                "id" => $purchase['purchase_id'],
                "transaction_code" => $purchase['transaction_code'],
                "purchased_by" => $purchase['purchased_by'],
                "purchased_date" => date("M. d, 'y",strtotime($purchase['purchased_date'])),
                "payment_date" => date("M. d, 'y", strtotime($purchase['payment_date'])),
                "original_price" => $purchase_items->original_price ?? 0,
                "total_discount" => $purchase['total_discount'],
                "payment_gateway" => $purchase["payment_gateway"],
                "payment_method" => $purchase["payment_method"],
                "items" => $purchase_items->items,
            ];
        },$purchases_->toArray());
    
        return response()->json(["data"=>$data, "meta"=>$meta], 200);
    }

    function report_purchase_item_list(Request $request) : JsonResponse
    {

        $query = $request->all()['query'];

        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();

        if(array_key_exists("provider",$paramTable))
        {
           $items = $this->purchase_item_list($paramTable);
          return response()->json($items,200);
        }
      
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ? ($paramTable["pagination"]["perpage"] <= 0 ? 10 : $paramTable["pagination"]["perpage"]) : '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $purchase_items = Purchase_Item::select(
            "webinars.provider_id as web_provider_id","courses.provider_id as course_provider_id",
            "courses.title as course_name","webinars.title as webinar_name",
            "purchase_items.channel","purchase_items.price as original_price","purchase_items.total_amount as price_paid",
            "purchase_items.provider_revenue","purchase_items.fast_revenue","purchase_items.promoter_revenue","purchase_items.id as itemID",
            "purchase_items.type","purchase_items.voucher as coupon_code","purchase_items.discount", "purchase_items.discounted_price"
        )
        ->where("purchase_id",$query["purchaseID"])
        ->leftJoin("courses","courses.id","purchase_items.data_id")
        ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
        ->leftJoin("webinars","webinars.id","purchase_items.data_id");
                                        

        $quotient = floor($purchase_items->count() / $perPage);
        $reminder = ($purchase_items->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $purchase_items->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "provider_name",
        );
        if(array_key_exists("sort", $paramTable)){
            $purchase_items = $purchase_items->skip($offset)->take($perPage);   
        }else{
            $purchase_items = $purchase_items->skip($offset)->take(10);
        }

        $purchase_items = $purchase_items->get();
        $data = array_map(function($item){
       
        if($item['coupon_code'] == null && $item["discount"] == null ){
            $channel = "Fast";
        }else if($item['channel'] == "fast_promo"){
            $channel = "Fast";
        }else if($item['channel'] == "provider_promo"){
            $channel = "Provider";
        }else if($item['channel'] == "promoter_promo"){
            $channel = "Promoter";
        }else{
            $channel = "Refund";
        }
        $provider = $item['type'] == "course" ? Provider::where("id",$item['course_provider_id'])->first() :  Provider::where("id",$item['web_provider_id'])->first();
        $percent = _get_revenue_percent($provider->id,$item['channel'],$item['type']);
        
            return[
                "id" => $item['itemID'],
                "provider" =>  $provider->name,
                "product" => $item['type'] == "course" ? $item['course_name'] : $item["webinar_name"],
                "type" => ucwords($item['type']),
                "channel" => ucwords($channel),
                "original_price" => $item['original_price'],
                "discounted_price" => $item['discounted_price'],
                "provider_revenue" => number_format($item['provider_revenue'],2),
                "fast_revenue" => number_format($item['fast_revenue'],2),
                "affiliate_revenue" => number_format($item['promoter_revenue'],2),
                "provider_comm" => $percent["provider"]."%",
                "fast_comm" => $percent['fast']."%",
                "affiliate_comm" => $percent['promoter']."%",
            ];
        },$purchase_items->toArray());


    
        return response()->json(["data"=>$data, "meta" =>$meta], 200);
    }

    function purchase_item_list($paramTable){
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ? ($paramTable["pagination"]["perpage"] <= 0 ? 10 : $paramTable["pagination"]["perpage"]) : '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        $query = $paramTable;
       
        $start_date = date("Y-m-d",strtotime($query['start_date']));
        $end_date = date("Y-m-d",strtotime($query['end_date']));
    
        if($query['provider'][0] == 0 || $query['provider'][0] == "0"){
            $purchase_items = Purchase_Item::select(
                                                "providers.name as provider_name","providers.id as provider_id","courses.title as course_name","webinars.title as webinar_name",
                                                "purchase_items.channel","purchase_items.price as original_price","purchase_items.total_amount as price_paid",
                                                "purchase_items.provider_revenue","purchase_items.fast_revenue","purchase_items.promoter_revenue","purchase_items.id as itemID",
                                                "purchase_items.type","purchase_items.voucher as coupon_code","purchase_items.discount","purchases.reference_number as transaction_code","users.name as purchased_by",
                                                "purchase_items.created_at as purchased_date","purchases.updated_at as payment_date"
                                            )
                                            ->where("purchases.payment_status","paid")
                                            ->whereDate("purchases.updated_at",">=",$start_date)->whereDate("purchases.updated_at","<=",$end_date)
                                            ->leftJoin("users","users.id","purchase_items.user_id")
                                            ->leftJoin("courses","courses.id","purchase_items.data_id")
                                            ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                                            ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                                            ->leftJoin("providers",function($join){
                                                $join->on("providers.id","webinars.provider_id");
                                                $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                                                $join->orOn("providers.id","courses.provider_id");
                                                $join->on("purchase_items.type","=",DB::raw("'course'"));
                                            });
        }else{
            $purchase_items = Purchase_Item::select(
                                "providers.name as provider_name","providers.id as provider_id","courses.title as course_name","webinars.title as webinar_name",
                                "purchase_items.channel","purchase_items.price as original_price","purchase_items.total_amount as price_paid",
                                "purchase_items.provider_revenue","purchase_items.fast_revenue","purchase_items.promoter_revenue","purchase_items.id as itemID",
                                "purchase_items.type","purchase_items.voucher as coupon_code","purchase_items.discount","purchases.reference_number as transaction_code","users.name as purchased_by",
                                "purchase_items.created_at as purchased_date","purchases.updated_at as payment_date"
                            )
                            ->where("purchases.payment_status","paid")
                            ->whereIn("providers.id",$query['provider'])
                            ->whereDate("purchases.updated_at",">=",$start_date)->whereDate("purchases.updated_at","<=",$end_date)
                            ->leftJoin("users","users.id","purchase_items.user_id")
                            ->leftJoin("courses","courses.id","purchase_items.data_id")
                            ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                            ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                            ->leftJoin("providers",function($join){
                                $join->on("providers.id","webinars.provider_id");
                                $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                                $join->orOn("providers.id","courses.provider_id");
                                $join->on("purchase_items.type","=",DB::raw("'course'"));
                            });
                            
        }
        
        $quotient = floor($purchase_items->count() / $perPage);
        $reminder = ($purchase_items->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $purchase_items->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "transaction_code",
        );
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array(
                                $paramTable["sort"]["field"], 
                                ["transaction_code", "voucher", "type","purchased_by","purchased_date","provider_name","course","channel","original_price","price_paid",
                                "provider_revenue","fast_revenue","affiliate_revenue","provider_comm","fast_comm","affiliate_comm"]) ? 
                                $paramTable["sort"]["field"] : 
                                "provider_name";

            $purchase_items = $purchase_items->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                            ->skip($offset)->take($perPage);
        }else{
            $purchase_items = $purchase_items->skip($offset)->take(10);
        }

        $purchase_items = $purchase_items->get();

        $data = array_map(function($item){

          
                if($item['coupon_code'] == null && $item["discount"] == null ){
                    $channel = "";
                }else if($item['channel'] == "fast_promo"){
                    $channel = "Fast Promo";
                }else if($item['channel'] == "provider_promo"){
                    $channel = "Provider Promo";
                }else if($item['channel'] == "promoter_promo"){
                    $channel = "Promoter Promo";
                }else{
                    $channel = "Refund";
                }
                $percent = _get_revenue_percent($item['provider_id'],$item['channel'],$item['type']);
               
                return[
                    "id" => $item['itemID'],
                    "transaction_code" => $item['transaction_code'],
                    "purchased_by" => $item['purchased_by'],
                    "purchased_date" => date("M. j 'y",strtotime($item['purchased_date'])),
                    "payment_date" => date("M. j 'y",strtotime($item['payment_date'])),
                    "provider" => $item['provider_name'],
                    "type" => $item['type'],
                    "course" => $item['type'] == "course" ? $item['course_name'] : $item["webinar_name"],
                    "type" => ucwords($item['type']),
                    "channel" => ucwords($channel),
                    "voucher" => $item['coupon_code'],
                    "original_price" => $item['original_price'],
                    "price_paid" => $item['price_paid'],
                    "provider_revenue" => number_format($item['provider_revenue'],2),
                    "fast_revenue" => number_format($item['fast_revenue'],2),
                    "affiliate_revenue" => number_format($item['promoter_revenue'],2),
                    "provider_comm" => $percent["provider"]."%",
                    "fast_comm" => $percent['fast']."%",
                    "affiliate_comm" => $percent['promoter']."%",
                ];
        

        },$purchase_items->toArray());

        return ["data"=>$data,"meta"=>$meta];
    }

    function get_revenue_percent($provider_id, $promo){
        
        $provider = Provider::find($provider_id);

        if($provider){
            switch ($promo) {
                case 'fast_promo':
                    return [
                        "fast" => $provider->fast_promo_comm,
                        "provider" => 100 - $provider->fast_promo_comm,
                        "promoter" => 0,
                    ];

                    break;

                case 'provider_promo':
                    return [
                        "fast" => 100 - $provider->provider_promo_comm,
                        "provider" => $provider->provider_promo_comm,
                        "promoter" => 0,
                    ];

                    break;
                
                
                default:
                    $left = 100 - $provider->promoter_promo_comm;
                    $half = $left > 0 ? $left/2 : 0;

                    return [
                        "fast" => $half,
                        "provider" => $half,
                        "promoter" => $provider->promoter_promo_comm,
                    ];

                    break;
            }
        }

        return [
            "fast" => 50,
            "provider" => 50,
            "promoter" => 0, 
        ];
    }
}

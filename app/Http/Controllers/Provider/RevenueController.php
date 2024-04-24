<?php

namespace App\Http\Controllers\Provider;

use App\{User, Provider, Logs, Co_Provider, Course,Provider_Permission, Payout, Purchase_Item,Voucher,Webinar};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\Object_;
use Response;
use Session;

class RevenueController extends Controller
{
    protected function list(Request $request)
    {   
       
        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
        $paramFilter = $request->session()->get('revenue_query');
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '12';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        $provider_id = _current_provider();
        $users = User::find(Auth::user()->id);
        
        $revenues = Payout::where("data_id",$provider_id->id)->where("type","provider");
        $total_revenues = Payout::where("data_id",$provider_id->id)->where("type","provider");

        if($paramFilter){
            if ($period = $paramFilter["period"]) {
              
                $this->filter($revenues, 'date_to', $period);
            }
            // if ($period = $paramFilter["expected_date"]) {
              
            //     $this->filter($revenues, 'expected_date', $period);
            // }
            if ($period = $paramFilter["notes"]) {
              
                $this->filter($revenues, 'provider_revenue', $period);
            }

            if ($amount = $paramFilter["amount"]) {
                $this->filter($revenues, 'provider_revenue', $amount);
            }

        }
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["period", "amount", "expected_date", "status"]) ? $paramTable["sort"]["field"] : "period";
            $properSort = ($retouch_sort == "period" ? "date_to" : "provider_revenue");
            $revenues = $revenues->orderBy($properSort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $revenues = $revenues->skip($offset)->take(12);
        }

        $revenues = $revenues->get();
        $quotient = floor($revenues->count() / $perPage);
        $reminder = ($total_revenues->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $total_revenues->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "period",
        );
        $data = array_map(function($revenue) use($request){
            $month_year = date("F Y",strtotime($revenue['date_from']."+1 month"));
            $expected_date =  date("F j, Y",strtotime($month_year."first friday"));
            if($revenue['status'] == "waiting"){
                $status = 1;
            }else if($revenue['status'] == "on-hold"){
                $status = 2;
            }else{
                $status =3;
            }
            return [
                "id"=>$revenue['id'],
                "period"=>date("F Y",strtotime($revenue['date_to'])),
                "amount"=>$revenue['provider_revenue'],
                "generated_url"=>date("F-Y",strtotime($revenue['date_to'])),
                "expected_date"=>date("j",strtotime($expected_date)) >= 8 ? date("F j, Y",strtotime("-7 day",strtotime($expected_date))) : $expected_date ,
                "status" => $status,
                "notes"=>$revenue['notes'],
            ];
        }, $revenues->toArray());
    
        return ["data"=>$data, "meta"=>$meta];
    }
    protected function filter($revenues, string $property, array $param)
    {
        $filter = $param["filter"];
        $values = $param["values"];

        if($values){
            foreach ($values as $key => $value) {
             
                if($key==0){
                    if($property == "date_to"){
                        $month_year = date_parse($value);
                        if($month_year["month"] != false && $month_year["year"] != false){
                            $revenues->whereYear("date_to",$filter,$month_year["year"])->whereMonth("date_to",$filter,$month_year["month"]);
                            
                        }else if($month_year["month"] != false){
                            $revenues->whereMonth("date_to",$filter,$month_year["month"]);
                        }else{
                            $revenues->whereYear("date_to",$filter,$month_year["year"]);
                        }
                        
                    }else{
                        $revenues->whereRaw($property._to_sql_operator($filter,$value));
                    }
                }else{
                    $revenues->orWhereRaw($property._to_sql_operator($filter, $value));
                }
            }
           
            return;
        }else{
            if($filter == "empty"){
                $revenues->where($property, "=", null);
            }

            if($filter == "!empty"){
                $revenues->where($property, "!=", null);
            }

            return;
        }
    }

    function query(Request $request){
        $request->session()->put("revenue_query", $request->all());
    }
    function getMonthlyDetails(Request $request):JsonResponse{
        $payout_id = $request->payout_id;
        $get_payout_details = Payout::select(
                            DB::raw("CAST(DATE_FORMAT(date_to,'%m') AS UNSIGNED) as month"),
                            DB::raw("CAST(DATE_FORMAT(date_to,'%Y') AS UNSIGNED) as year"),
                            "date_from",
                            "provider_revenue"
                        )->where("id",$payout_id)->first();
            $month_year = date("F Y",strtotime($get_payout_details->date_from."+1 month"));
            $expected_date =  date("F j, Y",strtotime($month_year."first friday"));
                        
        $data = [
            "month_earned" => number_format($get_payout_details->provider_revenue,2),
            "expected_date" =>date("j",strtotime($expected_date)) >= 8 ? date("F j, Y",strtotime("-7 day",strtotime($expected_date))) : $expected_date ,
        ];
       
        return response()->json(["data"=>$data], 200);
    }

    function monthlist(Request $request) : JsonResponse
    {   
        /**
         * Fake Data
         * 
         * 
         */
        $payout_id = $request->payout_id;
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $provider_id = _current_provider();
        $get_month_year = Payout::select(
                                DB::raw("CAST(DATE_FORMAT(date_to,'%m') AS UNSIGNED) as month"),
                                DB::raw("CAST(DATE_FORMAT(date_to,'%Y') AS UNSIGNED) as year")
                            )->where("id",$payout_id)->first();
   
        $get_purchases = Purchase_Item::select(
                        "purchase_items.type as type","webinars.title as webinar",
                        "purchase_items.id as purchase_id","purchase_items.created_at as purchase_date",
                        "purchases.updated_at as payment_date","users.name as customer",
                        "courses.title as course","purchase_items.voucher as coupon_code",
                        "purchase_items.channel as channel","purchase_items.price as original_price","purchase_items.total_amount",
                        "purchase_items.discount as discount","purchase_items.fast_revenue as fast_revenue",
                        "purchase_items.provider_revenue as provider_revenue"
                    )
                    ->whereMonth("purchases.updated_at",$get_month_year->month)
                    ->whereYear("purchases.updated_at",$get_month_year->year)
                    ->where("purchases.payment_status","paid")
                    //->where("purchase_items.fast_status","complete")
                    ->where("providers.id",$provider_id->id)
                    //->leftJoin("providers","providers.id","courses.provider_id")
                    // ->leftJoin("purchase_items","purchase_items.course_id","courses.id")
                    ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                    ->leftJoin("users","users.id","purchase_items.user_id")
                    ->leftJoin("courses","courses.id","purchase_items.data_id")
                    ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                    ->leftJoin("providers",function($join){
                        $join->on("providers.id","webinars.provider_id");
                        $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                        $join->orOn("providers.id","courses.provider_id");
                        $join->on("purchase_items.type","=",DB::raw("'course'"));
                    });
  

        $total = $get_purchases->count();
      
        $quotient = floor($get_purchases->count() / $perPage);
        $reminder = ($get_purchases->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $get_purchases->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "purchase_date",
        );
                 
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["purchase_date", "customer","type", "course", "coupon_code","channel","price_paid"]) ? $paramTable["sort"]["field"] : "purchase_date";
          
            $get_purchases = $get_purchases->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                            ->skip($offset)->take($paramTable["pagination"]["perpage"]);
                  
        }else{
            if($perPage){
                $get_purchases = $get_purchases->skip($offset)->take($perPage);
            }else{
                $get_purchases = $get_purchases->skip($offset)->take(10);
            }
           
        }
       $get_purchases = $get_purchases->get();
       
       $data= array_map(function($purchase) use($request){
        $provider_id = _current_provider();
            $original_price = $purchase["original_price"];
            if($purchase['coupon_code'] == null && $purchase["discount"] == null ){
                $channel = "";
            }else if($purchase['channel'] == "fast_promo"){
                $channel = "Fast Promo";
            }else if($purchase['channel'] == "provider_promo"){
                $channel = "Provider Promo";
            }else if($purchase['channel'] == "promoter_promo"){
                $channel = "Promoter Promo";
            }else{
                $channel = "Refund";
            }
            $percentage = _get_revenue_percent($provider_id->id,$purchase['channel'],$purchase["type"]);
            $title = $purchase['type'] == "course" ? $purchase['course'] : $purchase['webinar'] ;
            return[
                "id" => $purchase['purchase_id'],
                "purchase_date" => date("M. d 'y", strtotime($purchase['purchase_date'])),
                "payment_date" => date("M. d 'y", strtotime($purchase['payment_date'])),
                "type" => $purchase['type'] == "course" ? "Video on Demand" : "Webinar",
                "customer" => ucwords($purchase['customer']),
                "course" =>$title,
                "coupon_code" => $purchase['coupon_code'],
                "channel" => $channel,
                "price_paid" =>  $purchase['total_amount'],
                "original_price" => $original_price,
                "revenue" => $purchase['provider_revenue'],
                "percent" => Auth::user()->superadmin == "none" ? $percentage["provider"] : $percentage["fast"],
            ];
       },$get_purchases->toArray());
      
    //    dd($data);
        $report = $request->month;
    
        return response()->json(["data"=>$data, "meta"=>$meta], 200);
    }

    function lastSixMonths(Request $request) : JsonResponse
    {   
        /**
         * Fake Data
         * 
         * 
         */
        $report = $request->month;
        $pagination = $request->pagination;
        $sort = $request->sort;
        $provider_id = _current_provider();

        if((Auth::user()->superadmin) == "active"){
            $latestRevenues = Payout::
                                select(
                                    DB::raw('sum(fast_revenue) as a'), 
                                DB::raw("CAST(DATE_FORMAT(date_to,'%c') AS UNSIGNED) as x")
                                )->where("created_at",">",Carbon::today()->subMonths(7))->groupBy('x')->get();
            // $total_revenues = Payout::select(DB::raw('sum(provider_revenue) as total_revenue'))->first();

        }else{
            $users = User::find(Auth::user()->id);
            $latestRevenues = Payout::
                            select(
                                DB::raw('sum(provider_revenue) as a'), 
                               DB::raw("CAST(DATE_FORMAT(date_to,'%c') AS UNSIGNED) as x")
                            )->where("created_at",">",Carbon::today()->subMonths(7))->groupBy('x')
                            ->where("data_id",$provider_id->id)->where("type","provider")->get();
        }

        $total_revenues = Purchase_Item::select(
                                DB::raw('sum(purchase_items.provider_revenue) as total_revenue')
                        )
                        ->where("providers.id",$provider_id->id)
                        ->where("purchase_items.payment_status","paid")
                        ->leftJoin("courses","courses.id","purchase_items.data_id")
                        ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                        ->leftJoin("providers",function($join){
                            $join->on("providers.id","webinars.provider_id");
                            $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                            $join->orOn("providers.id","courses.provider_id");
                            $join->on("purchase_items.type","=",DB::raw("'course'"));
                        })
                        ->leftJoin("users","users.id","purchase_items.user_id")->first();
        $data = [
                "latestRevenues"=>$latestRevenues,
                "total_revenues"=>number_format($total_revenues->total_revenue,2)
        ];
    
        return response()->json($data, 200);
    }
    function totalearnings(Request $request):JsonResponse{

        $payout_id = $request->payout_id;
        $provider_id = _current_provider();

        if($payout_id){
            $get_month_year = Payout::select(
                                    DB::raw("CAST(DATE_FORMAT(date_to,'%m') AS UNSIGNED) as month"),
                                    DB::raw("CAST(DATE_FORMAT(date_to,'%Y') AS UNSIGNED) as year")
                                )->where("id",$payout_id)->first();
        }else{

        }
        
        $get_purchases = Purchase_Item::select(
                               DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"),
                               DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                               DB::raw("sum(purchase_items.promoter_revenue) as endorser_revenue")
                            )
                            ->whereMonth("purchases.updated_at",$get_month_year->month)
                            ->whereYear("purchases.updated_at",$get_month_year->year)
                            ->where("purchase_items.payment_status","paid")
                            //->where("purchase_items.fast_status","complete")
                            
                            ->where("providers.id",$provider_id->id)
                            ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                            ->leftJoin("courses","courses.id","purchase_items.data_id")
                            ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                            ->leftJoin("providers",function($join){
                                $join->on("providers.id","webinars.provider_id");
                                $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                                $join->orOn("providers.id","courses.provider_id");
                                $join->on("purchase_items.type","=",DB::raw("'course'"));
                            })
                            ->get();

        return response()->json(["data"=>$get_purchases[0]],200);
    }
    function promotionActivity(Request $request):JsonResponse{
        $payout_id = $request->payout_id;
        $provider_id = _current_provider();

        if($payout_id){
            $get_month_year = Payout::select(
                                    DB::raw("CAST(DATE_FORMAT(date_to,'%m') AS UNSIGNED) as month"),
                                    DB::raw("CAST(DATE_FORMAT(date_to,'%Y') AS UNSIGNED) as year")
                                )->where("id",$payout_id)->first();
        }else{

        }
       
        $vouchers = Voucher::where("provider_id",$provider_id->id)
                            ->where("status","active")->get();
        $data = [];
       $promotion_legend = [];
       $total_promotion = 0;
        foreach($vouchers as $voucher){
            $promotions = Purchase_Item::where("purchase_items.voucher",$voucher->voucher_code)
                                    ->where("purchases.payment_status","paid")
                                    //->where("purchase_items.fast_status","complete")
                                    ->whereMonth("purchases.updated_at",$get_month_year->month)
                                    ->whereYear("purchases.updated_at",$get_month_year->year)
                                    ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                                    ->count();
            array_push($data,$promotions);
          
            array_push($promotion_legend,[$voucher->voucher_code => $promotions]);
            $total_promotion += $promotions;
        }
        
       
       $promotion = array_map(function($voucher) use($request){
           return $voucher['voucher_code'];
       },$vouchers->toArray());
//dd($promotion_legend);
        return response()->json(["data"=>$data,"vouchers" => $promotion,"promotion_legend" => $promotion_legend,"total_promotion"=>$total_promotion],200);
    }
    function earningsByCourse (Request $request):JsonResponse{
        $provider_id = _current_provider();
        $payout_id = $request->payout_id;
        if($payout_id){
            $get_month_year = Payout::select(
                                    DB::raw("CAST(DATE_FORMAT(date_to,'%m') AS UNSIGNED) as month"),
                                    DB::raw("CAST(DATE_FORMAT(date_to,'%Y') AS UNSIGNED) as year")
                                )->where("id",$payout_id)->first();
        }else{

        }
        $courses = Course::where("fast_cpd_status","live")
                           //->orwhere("fast_cpd_status","published")
                           ->where("provider_id",$provider_id->id)
                           ->get();
        $webinars = Webinar::where("fast_cpd_status","live")
                        //->orwhere("fast_cpd_status","published")
                        ->where("provider_id",$provider_id->id)
                        ->get();
        $data = [];
        $courses_list_legend = [];
        $total_courses_earned = 0;
        foreach($courses as $course){
            $course_earnings = Purchase_Item::select(
                                            DB::raw("sum(purchase_items.provider_revenue) as course_earning")
                                        )
                                        ->where("purchase_items.data_id",$course->id)
                                        ->where("purchase_items.type","course")
                                        ->where("purchases.payment_status","paid")
                                        //->where("fast_status","complete")
                                        ->whereMonth("purchases.updated_at",$get_month_year->month)
                                        ->whereYear("purchases.updated_at",$get_month_year->year)
                                        ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                                        ->get();
            array_push($data,$course_earnings[0]->course_earning);
            array_push($courses_list_legend,[$course->title => $course_earnings[0]->course_earning]);
            $total_courses_earned+=$course_earnings[0]->course_earning;
        }
        foreach($webinars as $webinar){
            $webinar_earnings = Purchase_Item::select(
                                            DB::raw("sum(purchase_items.provider_revenue) as course_earning")
                                        )
                                        ->where("purchase_items.data_id",$webinar->id)
                                        ->where("purchase_items.type","webinar")
                                        ->where("purchases.payment_status","paid")
                                        //->where("fast_status","complete")
                                        ->whereMonth("purchases.updated_at",$get_month_year->month)
                                        ->whereYear("purchases.updated_at",$get_month_year->year)
                                        ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                                        ->get();
            array_push($data,$webinar_earnings[0]->course_earning);
            array_push($courses_list_legend,[$webinar->title => $course_earnings[0]->course_earning]);
            $total_courses_earned+=$course_earnings[0]->course_earning;
        }
        $courses_list = array_map(function($course) use($request){
            return $course['title'];
        },$courses->toArray());
   
        return response()->json(["data"=>$data,"courses"=>$courses_list,"courses_list_legend"=>$courses_list_legend,"total_courses_earned"=>$total_courses_earned],200);
    }

}

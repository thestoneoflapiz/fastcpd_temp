<?php

namespace App\Http\Controllers\Superadmin;

use App\{User, Provider, Co_Provider, Instructor, Course, Payout};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class PayoutController extends Controller
{
    function verification_list(Request $request) : JsonResponse
    {
        $paramTable = $request->all();
        $paramFilter = $request->session()->get('user_query');
       // dd($paramFilter);
        $page = $paramTable["pagination"]["page"];
        $perPage =  $paramTable["pagination"]["perpage"] ? ($paramTable["pagination"]["perpage"] <= 0 ? 10 : $paramTable["pagination"]["perpage"]) : '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $payouts = Payout::select("payouts.id as payoutID","payouts.notes","providers.name as provider_name","users.name as full_name","payouts.status",
                                "payouts.provider_revenue","payouts.fast_revenue","payouts.promoter_revenue",
                                "payouts.date_to","payouts.type","payouts.data_id","providers.id as provider_id",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_from,'%m') AS UNSIGNED) as month"),"payouts.date_from",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_to,'%Y') AS UNSIGNED) as year"))
                                ->leftJoin("providers","providers.id","payouts.data_id")
                                ->leftJoin("users","users.provider_id","providers.id")
                                ->where("payouts.provider_revenue", ">", 0)
                                ->orderBy("year","desc")->orderBy("month","desc");

        if($paramFilter){
            if ($month = $paramFilter["month"]) {
              
               $payouts = $this->filter($payouts, 'month', $month);
            }
            // if ($period = $paramFilter["expected_date"]) {
              
            //     $this->filter($revenues, 'expected_date', $period);
            // }
            if ($year = $paramFilter["year"]) {
              
                $payouts =  $this->filter($payouts, 'year', $year);
            }

            if ($user_type = $paramFilter["user_type"]) {
                $payouts = $this->filter($payouts, 'type', $user_type);
            }
            if ($provider = $paramFilter["provider"]) {
              
                $payouts = $this->filter($payouts, 'provider_name', $provider);
            }

            if ($recipient = $paramFilter["recipient"]) {
                $this->filter($payouts, 'users.name', $recipient);
            }
            if ($amount = $paramFilter["amount"]) {
              
                $payouts =  $this->filter($payouts, 'amount', $amount);
            }

            if ($notes = $paramFilter["notes"]) {
                $payouts = $this->filter($payouts, 'type', $notes);
            }

        }
        $quotient = floor($payouts->count() / $perPage);
        $reminder = ($payouts->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $payouts->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "month",
        );
        
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["month", "year","type","provider_name","full_name"]) ? $paramTable["sort"]["field"] : "month";
            $payouts = $payouts->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $payouts = $payouts->skip($offset)->take(10);  
        }
       
        $payouts = $payouts->get();
    
        $data= array_map(function($payout) use($request){  
            $data_list = array();
            
            $month_year = date("F Y",strtotime($payout['date_from']."+1 month"));
            
            $expected_date =  date("M. d, 'y",strtotime($month_year."first friday"));
            if($payout["type"] == "provider"){
                $amount = $payout['provider_revenue'];
                $company_name = $payout['provider_name'];
                $fullname = $payout['full_name'];
            }else if($payout["type"] == "fast"){
                $amount = $payout['fast_revenue'];
                $company_name = "Fast CPD";
                $fullname = "Fast CPD";
            }else{
                $amount = $payout['promoter_revenue'];
                $company_name = "Promoter";
                $fullname = "Promoter";
            }
            if($payout['status'] == "waiting"){
                $status = 1;
            }else if($payout['status'] == "on-hold"){
                $status = 2;
            }else{
                $status =3;
            }
                $data = [
                    "id" => $payout['payoutID'],
                    "payout_id" => $payout['payoutID'],
                    "provider_id" => $payout['data_id'],
                    "month" => date("m",strtotime($payout['date_to'])),
                    "year" => date("Y",strtotime($payout['date_to'])),
                    "type" =>ucwords($payout["type"]),
                    "provider_name" =>$company_name,
                    "fast_cpd" => "Fast CPD",
                    "full_name" => $fullname,
                    "amount" => $amount,
                    "status" => $status,
                    "expected_payment_date" => date("j",strtotime($expected_date)) >= 8 ? date("M. d, 'y",strtotime("-7 day",strtotime($expected_date))) : $expected_date,
                    "notes" => $payout['notes'],
    
                ];
            
            return $data;
        },$payouts->toArray());
       
    
        return response()->json(["data"=>$data,"meta" => $meta, "total"=>count($data)], 200);
    }

    function report_list(Request $request) : JsonResponse
    {
        
        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
        $paramFilter = $request->session()->get('payouts_query');
        $page = $paramTable["pagination"]["page"];
        $perPage =  $paramTable["pagination"]["perpage"] ? ($paramTable["pagination"]["perpage"] <= 0 ? 10 : $paramTable["pagination"]["perpage"]) : '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        $month = $request->month;
        $year = $request->year;
        $user_type = $request->user_type;
     
        if($request->month == null || $request->year == null || $request->user_type == null){
            return response()->json([], 200);
        }else{
          
        }
      
        if($user_type[0] =="0"){
            $payouts = Payout::select("payouts.id as payoutID","providers.name as provider_name","users.name as full_name","payouts.status","payouts.notes",
                                "payouts.provider_revenue","payouts.fast_revenue","payouts.promoter_revenue",
                                "payouts.date_to","payouts.type","payouts.data_id","providers.id as provider_id",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_from,'%m') AS UNSIGNED) as month"),"payouts.date_from",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_to,'%Y') AS UNSIGNED) as year"))
                                ->whereIn(DB::raw("MONTH(date_to)"),$month)
                                ->where(DB::raw("YEAR(date_to)"),$year)
                                ->where("payouts.provider_revenue", ">", 0)
                                ->leftJoin("providers","providers.id","payouts.data_id")
                                ->leftJoin("users","users.provider_id","providers.id");
        }else{
            $payouts = Payout::select("payouts.id as payoutID","providers.name as provider_name","users.name as full_name","payouts.status","payouts.notes",
                                "payouts.provider_revenue","payouts.fast_revenue","payouts.promoter_revenue",
                                "payouts.date_to","payouts.type","payouts.data_id","providers.id as provider_id",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_from,'%m') AS UNSIGNED) as month"),"payouts.date_from",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_to,'%Y') AS UNSIGNED) as year"))
                            ->whereIn(DB::raw("MONTH(date_to)"),$month)
                            ->where(DB::raw("YEAR(date_to)"),$year)
                            ->whereIn("payouts.type",$user_type)
                            ->where("payouts.provider_revenue", ">", 0)
                            ->leftJoin("providers","providers.id","payouts.data_id")
                            ->leftJoin("users","users.provider_id","providers.id");
        }
       
        $quotient = floor($payouts->count() / $perPage);
        $reminder = ($payouts->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $payouts->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "month",
        );

      
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["month", "year","type","provider_name","full_name"]) ? $paramTable["sort"]["field"] : "month";
            $payouts = $payouts->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $payouts = $payouts->skip($offset)->take(10);  
        }
        $payouts = $payouts->get();
  
        $data= array_map(function($payout) use($request){  
            $data_list = array();
          
            $month_year = date("F Y",strtotime($payout['date_from']."+1 month"));
          
            $expected_date =  date("M. d, 'y",strtotime($month_year."first friday"));
            if($payout["type"] == "provider"){
                $amount = $payout['provider_revenue'];
                $company_name = $payout['provider_name'];
                $fullname = $payout['full_name'];
            }else if($payout["type"] == "fast"){
                $amount = $payout['fast_revenue'];
                $company_name = "Fast CPD";
                $fullname = "Fast CPD";
            }else{
                $amount = $payout['promoter_revenue'];
                $company_name = "Promoter";
                $fullname = "Promoter";
            }
            if($payout['status'] == "waiting"){
                $status = 1;
            }else if($payout['status'] == "on-hold"){
                $status = 2;
            }else{
                $status =3;
            }
                $data = [
                    "id" => uniqid(),
                    "payout_id" => $payout['payoutID'],
                    "provider_id" => $payout['data_id'],
                    "month" => date("m",strtotime($payout['date_to'])),
                    "year" => date("Y",strtotime($payout['date_to'])),
                    "type" =>ucwords($payout["type"]),
                    "provider_name" =>$company_name,
                    "fast_cpd" => "Fast CPD",
                    "full_name" => $fullname,
                    "amount" => $amount,
                    "expected_payment_date" => date("j",strtotime($expected_date)) >= 8 ? date("M. d, 'y",strtotime("-7 day",strtotime($expected_date))) : $expected_date,
                    "status" => $status,
                    "notes" => $payout['notes'],
    
                ];
         
            return $data;
        },$payouts->toArray());

        return response()->json(["data"=>$data,"meta" => $meta,"total"=>count($payouts)], 200);
    }

    function report_items_list(Request $request) : JsonResponse
    {
        $query = $request->all()['query'];

        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
        $paramFilter = $request->session()->get('revenue_query');
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '12';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $user_type = $request->userType;
        $month = $request->month;
        $year = $request->year;

        $payouts = Payout::select("payouts.id as payoutID","providers.name as provider_name","users.name as full_name",
                                "payouts.provider_revenue","payouts.fast_revenue","payouts.promoter_revenue",
                                "payouts.date_to","payouts.type","payouts.data_id","providers.id as provider_id"
                                
                            )
                            ->where(DB::raw("MONTH(date_to)"),$month)
                            ->where(DB::raw("YEAR(date_to)"),$year)
                            ->leftJoin("providers","providers.id","payouts.data_id")
                            ->leftJoin("users","users.provider_id","providers.id");
        $quotient = floor($payouts->count() / $perPage);
        $reminder = ($payouts->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $payouts->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "provider",
        );
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["provider", "name","amount","expected_payment_date"]) ? $paramTable["sort"]["field"] : "provider";
            $payouts = $payouts->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $payouts = $payouts->skip($offset)->take(10);  
        }       
        $payouts = $payouts->get();
        if($user_type == "Affiliate"){
            $data = array_map(function($payout){
                $month_year = date("F Y",strtotime($payout['date_to']."+1 month"));
                $expected_date =  date("M. d, 'y",strtotime($month_year."first friday"));
                return[
                    "id" => $payout['payoutID'],
                    "type" => $payout['type'],
                    "provider" => "Promoter",
                    "fast_cpd" => "Fast CPD",
                    "name" => "Promoter",
                    "amount" => $payout['promoter_revenue'],
                    "expected_payment_date" => date("j",strtotime($expected_date)) >= 8 ? date("M. d, 'y",strtotime("-7 day",strtotime($expected_date))) : $expected_date,
                    "notes" => $payout['promoter_revenue'] != 0 ? "Paid" : "No Payout",
                ];

            },$payouts->toArray());
            $data = array_filter($data, function($item){
                if($item['type'] == "affiliate")
                {
                    return $item;
                }
            });
        }else if($user_type == "Provider"){
            $data = array_map(function($payout){
                $month_year = date("F Y",strtotime($payout['date_to']."+1 month"));
                $expected_date =  date("M. d, 'y",strtotime($month_year."first friday"));
                return[
                    "id" => $payout['payoutID'],
                    "type" => $payout['type'],
                    "provider" => $payout['provider_name'],
                    "fast_cpd" => "Fast CPD",
                    "name" => $payout['full_name'],
                    "amount" => $payout['provider_revenue'],
                    "expected_payment_date" => date("j",strtotime($expected_date)) >= 8 ? date("M. d, 'y",strtotime("-7 day",strtotime($expected_date))) : $expected_date,
                    "notes" => $payout['provider_revenue'] != 0 ? "Paid" : "No Payout",
                ];

            },$payouts->toArray());
            $data = array_filter($data, function($item){
                if($item['type'] == "provider")
                {
                    return $item;
                }
            });
        }else{
            $data = array_map(function($payout){
                $month_year = date("F Y",strtotime($payout['date_to']."+1 month"));
                $expected_date =  date("M. d, 'y",strtotime($month_year."first friday"));
                return[
                    "id" => $payout['payoutID'],
                    "type" => $payout['type'],
                    "provider" => "Fast CPD",
                    "fast_cpd" => "Fast CPD",
                    "name" => "Fast CPD",
                    "amount" => $payout['fast_revenue'],
                    "expected_payment_date" => date("j",strtotime($expected_date)) >= 8 ? date("M. d, 'y",strtotime("-7 day",strtotime($expected_date))) : $expected_date,
                    "notes" => $payout['fast_revenue'] != 0 ? "Paid" : "No Payout",
                ];

            },$payouts->toArray());

            $data = array_filter($data, function($item){
                if($item['type'] == "fast")
                {
                    return $item;
                }
            });
        }

      

        return response()->json(["data"=>$data, "meta" => $meta], 200);
    }
    function payoutMonthList(){
        $oldestPayout = Payout::orderBy("date_to","asc")->first();
        $counterMonths = $oldestPayout ? ( (date("Y") - date("Y",strtotime($oldestPayout->date_to)) ) * 12 ) + 36 : 36;
        $year = $oldestPayout ? date("Y",strtotime($oldestPayout->date_to)) : date("Y");
        $monthCounter = 0;
        $monthList = collect();
        for($counter = 0; $counter < $counterMonths; $counter++){
            if($monthCounter < 12){
                for($user_counter = 0; $user_counter < 3; $user_counter++){
                    switch($user_counter){
                        case 0:
                            $user_type = "Promoter";
                        break;
                        case 1:
                            $user_type = "Provider";
                        break;
                        case 2:
                            $user_type = "FastCPD";
                        break;
                        default: 
                            $user_type = "Error";
                        break;
    
                    }
                    $datum =[
                        "id" => uniqid(),
                        "month" => $monthCounter,
                        "year" => $year,
                        "user_type" => $user_type
                    ];
                 
                    $monthList = $monthList->push($datum);
                
                   
                }
                $monthCounter++;
            }else{
                $year++;
                for($user_counter = 0; $user_counter < 3; $user_counter++){
                    switch($user_counter){
                        case 0:
                            $user_type = "Promoter";
                        break;
                        case 1:
                            $user_type = "Provider";
                        break;
                        case 2:
                            $user_type = "FastCPD";
                        break;
                        default: 
                            $user_type = "Error";
                        break;
    
                    }
                    $datum = [
                        "id" => uniqid(),
                        "month" => $monthCounter,
                        "year" => $year,
                        "user_type" => $user_type
                    ];
                    $monthList = $monthList->push($datum);
                }
                $monthCounter = 1;
            }
        }
       
       return $monthList;
    }
    function payout_details(Request $request):JsonResponse{
      $payout_id = $request->payout_id;

        $payouts = Payout::select("payouts.id as payoutID","payouts.notes","providers.name as provider_name","users.name as full_name","payouts.status",
        "payouts.provider_revenue","payouts.fast_revenue","payouts.promoter_revenue",
        "payouts.date_to","payouts.type","payouts.data_id","providers.id as provider_id",
        DB::raw("CAST(DATE_FORMAT(payouts.date_from,'%m') AS UNSIGNED) as month"),"payouts.date_from",
        DB::raw("CAST(DATE_FORMAT(payouts.date_to,'%Y') AS UNSIGNED) as year"))
        ->where("payouts.id",$payout_id)
        ->leftJoin("providers","providers.id","payouts.data_id")
        ->leftJoin("users","users.provider_id","providers.id")->get();
        $data= array_map(function($payout) use($request){  

            
            $month_year = date("F Y",strtotime($payout['date_from']."+1 month"));
            
            $expected_date =  date("M. d, 'y",strtotime($month_year."first friday"));
            if($payout["type"] == "provider"){
                $amount = $payout['provider_revenue'];
                $company_name = $payout['provider_name'];
                $fullname = $payout['full_name'];
            }else if($payout["type"] == "fast"){
                $amount = $payout['fast_revenue'];
                $company_name = "Fast CPD";
                $fullname = "Fast CPD";
            }else{
                $amount = $payout['promoter_revenue'];
                $company_name = "Promoter";
                $fullname = "Promoter";
            }
            if($payout['status'] == "waiting"){
                $status = 1;
            }else if($payout['status'] == "on-hold"){
                $status = 2;
            }else{
                $status =3;
            }
            $month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
                $data = [
                    "id" => $payout['payoutID'],
                    "payout_id" => $payout['payoutID'],
                    "provider_id" => $payout['data_id'],
                    "month" => $month[date("m",strtotime($payout['date_to']))-1],
                    "year" => date("Y",strtotime($payout['date_to'])),
                    "type" =>ucwords($payout["type"]),
                    "provider_name" =>$company_name,
                    "fast_cpd" => "Fast CPD",
                    "full_name" => $fullname,
                    "amount" => $amount,
                    "status" => $payout['status'],
                    "expected_payment_date" => date("j",strtotime($expected_date)) >= 8 ? date("M. d, 'y",strtotime("-7 day",strtotime($expected_date))) : $expected_date,
                    "notes" => $payout['notes'],
    
                ];
            
            return $data;
        },$payouts->toArray());

        return response()->json($data,200);
    }
    function update_payouts(Request $request):JsonResponse{
        $status = $request->status_selection;
        $notes = $request->notes;
        $payout_id = $request->payout_id;

        $payouts = Payout::where("id",$payout_id)->update([
            "status" => $status,
            "notes" => $notes
        ]);
        if($payouts){
            return response()->json(["status"=>200,"message"=>"Payout successfully updated!"],200);
        }
        return response()->json(["status" => 500,"message" => "Error! Payout status unchanged!"],500);
    }
    protected function filter($revenues, string $property, array $param)
    {
        $filter = $param["filter"];
        $values = $param["values"];

        if($values){
            foreach ($values as $key => $value) {
             
                if($key==0){
                    //  dd(date_parse($value));
                    if($property == "month"){
                        $month_year = date_parse($value);
                        if($month_year["month"] != false && $month_year["year"] != false){
                            $revenues->whereYear("date_to",$filter,$month_year["year"])->whereMonth("date_to",$filter,$month_year["month"]);
                            
                        }else if($month_year["month"] != false){
                            $revenues->whereMonth("date_to",$filter,$month_year["month"]);
                        }else{
                            $revenues->whereYear("date_to",$filter,$month_year["year"]);
                        }
                        
                    }else if($property == "year"){
                        $revenues->whereYear("date_to",$filter,$value);
                    }else{
                        $revenues->whereRaw($property._to_sql_operator($filter,$value));
                    }
                }else{
                    $revenues->orWhereRaw($property._to_sql_operator($filter, $value));
                }
            }
           
            return $revenues;
        }else{
            if($filter == "empty"){
                $revenues->where($property, "=", null);
            }

            if($filter == "!empty"){
                $revenues->where($property, "!=", null);
            }

            return $revenues;
        }
    }
}

<?php

namespace App\Http\Controllers\Superadmin;

use App\{
    User, 
    Provider, Course, Webinar,
    Purchase, Purchase_Item,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use App\Mail\{RejectedVoucher, ApprovedVoucher};

use App\Jobs\{EmailBigQueries};

use Response; 
use Session;


class DragonPayController extends Controller
{
    function list(Request $request) : JsonResponse
    {
        return response()->json($this->paginatedList($request));
    }

    function session(Request $request) : JsonResponse
    {
        $request->session()->put("dragonpay_session", $request->all());
        return response()->json([]);
    }

    protected function paginatedList(Request $request)
    {   
        $payments = Purchase::select(
            "purchases.id", "purchases.reference_number", "user.name as customer", "user.email as email", "purchases.total_amount as amount", "purchases.payment_status"
        )
        ->join("users as user", "user.id", "=", "purchases.user_id", "left")
        ->where("purchases.payment_gateway", "=", "dragonpay");
    
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
        $paramFilter = $request->session()->get("dragonpay_session"); 
        if($paramFilter){
            if ($reference_number = $paramFilter["reference_number"]) {
                $this->filter($payments, 'purchases.reference_number', $reference_number);
            }

            if ($customer = $paramFilter["customer"]) {
                $this->filter($payments, 'user.name', $customer);
            }

            if ($email = $paramFilter["email"]) {
                $this->filter($payments, 'user.email', $email);
            }

            if ($amount = $paramFilter["amount"]) {
                $this->filter($payments, 'purchases.total_amount', $amount);
            }

            if ($payment_status = $paramFilter["payment_status"]) {
                if($payment_status["filter"]!="all" && $payment_status["filter"]!=null){
                    $payments->where("purchases.payment_status", "=", $payment_status["filter"]);
                }
            }
        }

        $payment_count = $payments->get()->count();
        $payment_count_ids = array_map(function($ids){
            return $ids["id"];
        }, $payments->get()->toArray());

        if(array_key_exists("sort", $paramTable)){
            if(in_array($paramTable["sort"]["field"], ["customer", "email"])){
                $payments = $payments->orderBy("user.".($paramTable["sort"]["field"]=='customer' ? 'name' :$paramTable["sort"]["field"]), $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);

            }else{
                $payments = $payments->orderBy("purchases.".($paramTable["sort"]["field"]=="amount" ? "total_amount" : $paramTable["sort"]["field"]), $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
            }
        }else{
            $payments = $payments->orderBy("purchases.payment_status", "desc")->skip($offset)->take(10);
        }
 
        $payments = $payments->get();
        $quotient = floor($payments->count() / $perPage);
        $reminder = ($payments->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        
        return array(
            "meta"=> array(
                "page"=> $paramTable["pagination"]["page"] ?? '1',
                "pages"=> $paramTable["pagination"]["pages"] ?? $pagesScold,
                "perpage"=> $perPage,
                "total"=> $payment_count,
                "rowIds"=> $payment_count_ids,
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> $payments->toArray()
        );
    }

    /**
     * 
     * Standard Filter
     * 
     */
    protected function filter($payments, string $property, array $param)
    {
        $filter = $param["filter"];
        $values = $param["values"];

        if($values){
            foreach ($values as $key => $value) {
                if($value){
                    if($key==0){
                        $payments->whereRaw($property._to_sql_operator($filter, $value));
                    }else{
                        $payments->orWhereRaw($property._to_sql_operator($filter, $value));
                    }
                }
            }

            return;
        }else{
            if($filter == "empty"){
                $payments->where($property, "=", null);
            }

            if($filter == "!empty"){
                $payments->where($property, "!=", null);
            }

            return;
        }
    }

    function view_purchase_record(Request $request) : JsonResponse
    {
        $purchase = Purchase::find($request->purchase_id);
        $purchase->payment_at = $purchase->payment_at ? date("F d, Y h:i A") : "---";
        if($purchase){
            $items = Purchase_Item::select(
                "voucher", "discount", "total_amount as price",
                "payment_status", "channel", "type", "data_id"
            )
            ->where([
                "purchase_id" => $request->purchase_id,
                "user_id" => $purchase->user_id,
            ])->get(); 
            
            $items = array_map(function($item){
                if($item["type"]=="course"){
                    $data_record = Course::select("title")->find($item["data_id"]);
                }else{
                    $data_record = Webinar::select("title")->find($item["data_id"]);
                }

                $item["title"] = ucwords($item["type"]).": {$data_record->title}";
 
                return $item;
            }, $items->toArray());

            $purchase->items = $items;
        }
        return response()->json([
            "data" => $purchase ?? null,
        ]);
    }

    function set_payment_status(Request $request) : JsonResponse
    {
        $purchase = Purchase::find($request->purchase_id);
        if($purchase){
            $purchase->payment_status = $request->payment_status;
            $purchase->fast_status = $request->payment_status=="paid" ? "confirmed" : "waiting";
            if($purchase->save()){
                Purchase_Item::where([
                    "purchase_id" => $request->purchase_id,
                ])->update([
                    "payment_status" => $request->payment_status,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);


                if($request->payment_status=="paid"){
                    _notification_insert("purchase", $purchase->user_id, $purchase->reference_number, "Successful Payment", "Your transaction payment with ref#{$purchase->reference_number} is completed & confirmed by FastCPD Management", "/profile/settings");
                    $user = User::find($purchase->user_id);
                    if($user){
                        $this->dispatch(new EmailBigQueries($user->email, "confirmed_purchase", $purchase->reference_number));
                    }
                }

                return response()->json([]);
            }
        }

        return response()->json([
            "message" => "Purchase Record#{$request->purchase_id} not found!"
        ], 422);
    }
}
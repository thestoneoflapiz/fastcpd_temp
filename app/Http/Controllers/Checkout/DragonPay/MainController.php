<?php

namespace App\Http\Controllers\Checkout\DragonPay;

use App\{
    User, Provider, Course,
    Purchase, Purchase_Item, My_Cart
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use GuzzleHttp\Client;

use App\Jobs\{EmailBigQueries};

use Response; 
use Session;

class MainController extends Controller
{
    protected $dragonpay_;

    public function __construct() 
    {
        $this->dragonpay_ = [
            "url" => config('services.dragonpay.url'),
            "secret" => config('services.dragonpay.secret'),
            "id" => config('services.dragonpay.id'),
        ];
    }

    function payment_process(Request $request) : JsonResponse
    {
        $method = $request->method;

        $CHECKOUT_AMOUNT_ = _get_checkout_items();
        $real_price = $CHECKOUT_AMOUNT_["total_amount"];
        $real_price = number_format($real_price, 2, '.', '');
       
        if($real_price==0){
            return response()->json([
                "message" => 'No Payment found! Please refresh your browser. Thank you!'
            ], 422);
        }

        $reference_number = date("Y").strtoupper(uniqid());
        $user_email = Auth::user()->email;

        $purchase = new Purchase;
        $purchase->user_id = Auth::user()->id;
        $purchase->reference_number = $reference_number;

        $purchase->payment_gateway = "dragonpay";
        $purchase->payment_method = strtolower($method);
        $purchase->payment_status = "waiting";

        $purchase->vouchers = count($CHECKOUT_AMOUNT_["vouchers"]) > 0 ? json_encode($CHECKOUT_AMOUNT_["vouchers"]) : null;
        $purchase->total_discount = $CHECKOUT_AMOUNT_["total_discount_percent"] ?? null;
        $purchase->total_amount = $CHECKOUT_AMOUNT_["total_amount"] ?? null;

        $purchase->created_at = date("Y-m-d H:i:s");
        $purchase->updated_at = date("Y-m-d H:i:s");

        if($purchase->save()){
            $this->move_to_checkout_mycart($CHECKOUT_AMOUNT_["my_cart"], $purchase);
           
            $data = [
                "procid"=> $method,
                "merchantid" => $this->dragonpay_['id'],
                "txnid" => $reference_number,
                "amount" => $real_price,
                "ccy" => "PHP",
                "description" => "PAYMENTFOR#{$reference_number}",
                "email" => $user_email,
            ];

            /**
             * security
             * 
             */
            $stringify = "{$this->dragonpay_['id']}:{$reference_number}:{$real_price}:PHP:PAYMENTFOR#{$reference_number}:{$user_email}:{$this->dragonpay_['secret']}";
            $data["digest"] = hash('sha1', $stringify);
            $url_query = "{$this->dragonpay_['url']}?".http_build_query($data);
            
            _notification_insert("purchase", Auth::user()->id, $reference_number, "Waiting for Confirmation of Payment", "Your transaction payment with ref#{$reference_number} is currently processing", "/profile/settings");
            $this->dispatch(new EmailBigQueries(Auth::user()->email, "pending_purchase", $reference_number));
        
            return response()->json([
                "redirect" => $url_query,
            ]);
        }

        return response()->json([
            "message" => "Unable to proceed checkout! Please refresh your browser"
        ], 422);
    }

    function move_to_checkout_mycart($mycart, $purchase){
        foreach ($mycart as $item) {
            $ending_price = $item["total_amount"];

            $fast_revenue = 0;
            $provider_revenue = 0;
            $promoter_revenue = 0;

            $revenue_percentage =  _get_revenue_percent($item["provider_id"], $item["channel"], $item["type"]);
            $fast_revenue = ($revenue_percentage["fast"] / 100) * $ending_price;
            $provider_revenue = ($revenue_percentage["provider"] / 100) * $ending_price;
            $promoter_revenue = $revenue_percentage["promoter"] ? ($revenue_percentage["promoter"] / 100) * $ending_price : 0;

            _update_referral_voucher($item["voucher"], $item["discount"], true);
            Purchase_Item::insert([
                "purchase_id" => $purchase->id,
                "user_id" => Auth::user()->id,
                "type" => $item["type"],
                "data_id" => $item["data_id"],

                "credited_cpd_units" => $item["accreditation"] ? json_encode($item["accreditation"]) : null,
                "price" =>  $item["price"], 
                "discounted_price" =>  $item["discounted_price"], 
                "discount" => $item["discount"],
                "voucher" => $item["voucher"],
                "channel" => $item["channel"],

                "total_amount" =>  $item["total_amount"], 

                "offering_type" =>  $item["offering_type"], 
                "schedule_type" =>  $item["schedule_type"], 
                "schedule_id" =>  $item["schedule_id"], 

                "fast_revenue" => $fast_revenue,
                "provider_revenue" => $provider_revenue,
                "promoter_revenue" => $promoter_revenue,
                
                "payment_status" => "pending",
                "fast_status" => "incomplete",
                
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);

            My_Cart::where([
                "user_id" => Auth::user()->id,
                "type" => $item["type"],
                "data_id" => $item["data_id"],
                "status" => "active",
            ])->update([
                "status" => "checkout"
            ]);
        }
    }

    function pp_continue(Request $request) : JsonResponse
    {
        $purchase_record = Purchase::where([
            "reference_number" => $request->reference_number,
            "payment_status" => "waiting",
            "payment_gateway" => "dragonpay"
        ])->first();

        if($purchase_record){
            $method = strtoupper($purchase_record->payment_method);
            $real_price = number_format($purchase_record->total_amount, 2, '.', ',');
            $reference_number = $request->reference_number;
            $user_email = Auth::user()->email;

            $data = [
                "procid"=> $method,
                "merchantid" => $this->dragonpay_['id'],
                "txnid" => $reference_number,
                "amount" => $real_price,
                "ccy" => "PHP",
                "description" => "PAYMENTFOR#{$reference_number}",
                "email" => $user_email,
            ];

            /**
             * security
             * 
             */
            $stringify = "{$this->dragonpay_['id']}:{$reference_number}:{$real_price}:PHP:PAYMENTFOR#{$reference_number}:{$user_email}:{$this->dragonpay_['secret']}";
            $data["digest"] = hash('sha1', $stringify);
            $url_query = "{$this->dragonpay_['url']}?".http_build_query($data);

            return response()->json([
                "redirect" => $url_query,
            ]);
        }

        return response()->json([
            "message" => "Reference {$request->reference_number} not found! Please refresh your browser"
        ], 422);
    }

    function pp_cancel(Request $request) : JsonResponse
    {
        $purchase_record = Purchase::where([
            "reference_number" => $request->reference_number,
            "payment_gateway" => "dragonpay"
        ])->whereIn("payment_status", ["waiting", "pending"])->first();

        if($purchase_record){
            $purchase_record->payment_status = "cancelled";
            $purchase_record->updated_at = date("Y-m-d H:i:s");
            if($purchase_record->save()){

                $items = Purchase_Item::where("purchase_id", "=", $purchase_record->id)->get();
                foreach ($items as $item) {
                    $item->payment_status = "cancelled";
                    $item->updated_at = date("Y-m-d H:i:s");
                    _update_referral_voucher($item->voucher, $item->discount, false);

                    $item->save();
                }

                _notification_insert("purchase", Auth::user()->id, $purchase_record->reference_number, "Cancellation of Payment", "Your transaction payment with ref#{$purchase_record->reference_number} is cancelled", "/profile/settings");
                $user = User::find($purchase_record->user_id);
                $this->dispatch(new EmailBigQueries(Auth::user()->email, "cancelled_purchase", $purchase_record->reference_number));
            
                return response()->json([]);
            }

            return response()->json([
                "message" => "Unable to cancel order! Please try again later"
            ], 422);
        }

        return response()->json([
            "message" => "Reference {$request->reference_number} not found! Please refresh your browser"
        ], 422);
    }
}

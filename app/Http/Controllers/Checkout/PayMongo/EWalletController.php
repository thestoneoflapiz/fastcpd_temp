<?php

namespace App\Http\Controllers\Checkout\PayMongo;

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

class EWalletController extends Controller 
{

    protected $paymongo_;

    public function __construct() 
    {
        $this->paymongo_ = [
            "secret" => base64_encode(config('services.paymongo.secret')),
            "public" => config('services.paymongo.public'),
        ];
    }

    function payment_form(Request $request){

        return view("page.cart.payment_forms.e-wallet", [
            "method" => $request->method
        ]);
    }

    function payment_process(Request $request) : JsonResponse
    {
        $reference_number = date("Y").strtoupper(uniqid());

        $CHECKOUT_AMOUNT_ = _get_checkout_items();
        $real_price = $CHECKOUT_AMOUNT_["total_amount"];
        $real_price = number_format($real_price, 2, '.', '');
        $real_price_clean = (int)str_replace(".", "", $real_price);

        if($real_price==0){
            return response()->json([
                "message" => 'No Payment found! Please refresh your browser. Thank you!'
            ], 422);
        }
        
        /**
         * make source
         * and save id of source
         * 
         */
        $client = new Client;
        $response = $client->post("https://api.paymongo.com/v1/sources", [
            "headers" => [
                "content-type" => "application/json",
                "authorization" => "Basic {$this->paymongo_['secret']}"
            ],
            "json" => [
                "data" => [
                    "attributes" => [
                        "type" => $request->method,
                        "amount" => $real_price_clean,
                        "currency" => "PHP",
                        "redirect" => [
                            "success" => url("/profile/settings"),
                            "failed" => url("/payment/error/{$request->method}"),
                        ],
                    ]
                ]
            ]
        ]);
        
        if( $response->getStatusCode() == 200){
            $result = $response->getBody()->getContents();
            $result = json_decode($result); 
            $data = $result->data;
            /**
             * 
             */
            $purchase = new Purchase;
            $purchase->user_id = Auth::user()->id;
            $purchase->reference_number = $reference_number;

            $purchase->payment_client_id = $data->id;
            $purchase->payment_gateway = "paymongo";
            $purchase->payment_method = $request->method;
            $purchase->payment_status = "pending";
            $purchase->payment_notes = $data->attributes->redirect->checkout_url;


            $purchase->vouchers = count($CHECKOUT_AMOUNT_["vouchers"]) > 0 ? json_encode($CHECKOUT_AMOUNT_["vouchers"]) : null;
            $purchase->total_discount = $CHECKOUT_AMOUNT_["total_discount_percent"] ?? null;
            
            $purchase->total_amount = $real_price;
            
            $purchase->created_at = date("Y-m-d H:i:s");
            $purchase->updated_at = date("Y-m-d H:i:s");
            $purchase->save();

            $this->move_to_checkout_mycart($CHECKOUT_AMOUNT_["my_cart"], $purchase);

            _notification_insert("purchase", Auth::user()->id, $reference_number, "Waiting for Payment", "Your transaction payment with ref#{$reference_number} is currently processing", "/profile/settings");
            $this->dispatch(new EmailBigQueries(Auth::user()->email, "pending_purchase", $reference_number));
            

            return response()->json([
                "redirect" => $data->attributes->redirect->checkout_url,
                "id" => Auth::user()->id,
            ]);
        }

        return response()->json(null, 422);
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

    function pp_cancel(Request $request) : JsonResponse
    { 
        $purchase_record = Purchase::where([
            "reference_number" => $request->reference_number,
            "payment_gateway" => "paymongo"
        ])->whereIn("payment_status", ["waiting", "pending"])
        ->whereIn("payment_method", ["gcash", "grab_pay"])->first();

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
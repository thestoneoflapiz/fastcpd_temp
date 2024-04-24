<?php

namespace App\Http\Controllers\WebHooks;

use App\{
    User, Provider, Course,
    Purchase, Purchase_Item, My_Cart, CLogs
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use GuzzleHttp\Client;

use App\Jobs\{EmailBigQueries};

use Response; 
use Session;

class PayMongoController extends Controller
{
    protected $paymongo_;

    public function __construct() 
    {
        $this->paymongo_ = [
            "secret" => base64_encode(config('services.paymongo.secret')),
            "public" => config('services.paymongo.public'),
        ];
    } 

    function catch_hook(Request $request){
        $throw_counter = 0;

        /**
         * for securing payments and verify that it's paymongo
         * 
         */
        $signature = $request->header('Paymongo-Signature');
        $signatures = explode(",", $signature);
        $timestamp = $signatures[0];
        $testmd = $signatures[1];
        $livemd = $signatures[2];

        $tmstamp_raw = str_replace("t=", "", $timestamp);
        $lmd_raw = str_replace("li=", "", $livemd);
        $tstmd_raw = str_replace("te=", "", $testmd);

        $JSONpayLoad = $request->getContent();

        $my_signature = $tmstamp_raw.".".$JSONpayLoad;
        $hooks_signature = $this->_valid_payment_hook($my_signature);

        if(count($hooks_signature) == 0){
            CLogs::insert(["message" => "E-wallet No webhook found at ".date("Y-m-d H:i:s")]);
            return response()->json([], 422);
        }

        if(in_array($tstmd_raw, $hooks_signature) || in_array($lmd_raw, $hooks_signature)){
            $post_data = $request->all()["data"]["attributes"];

            $purchase = Purchase::where([
                "payment_client_id" => $post_data["data"]["id"],
                "payment_status" => "pending",
            ])->first();

            if($purchase){
                $client = new Client;
                $response = $client->post("https://api.paymongo.com/v1/payments", [
                    "headers" => [
                        "content-type" => "application/json",
                        "authorization" => "Basic {$this->paymongo_['secret']}"
                    ],
                    "json" => [
                        "data" => [
                            "attributes" => [
                                "amount" => $post_data["data"]["attributes"]["amount"],
                                "description" => "REFERENCE # {$purchase->reference_number}",
                                "currency" => "PHP",
                                "source" => [
                                    "id" => $post_data["data"]["id"],
                                    "type" => "source",
                                ],
                            ]
                        ]
                    ]
                ]);
            
                if($response->getStatusCode() == 200){
                    $result = $response->getBody()->getContents();
                    $result = json_decode($result); 
                    $data = $result->data;
                    /**
                     * 
                     */
                    $purchase->payment_client_id = $data->id;
                    $purchase->payment_status = "paid";
                    $purchase->fast_status = "confirmed";
                    $purchase->updated_at = date("Y-m-d H:i:s");
                    $purchase->payment_at = date("Y-m-d H:i:s");
                    $purchase->save();

                    Purchase_Item::where([
                        "user_id" => $purchase->user_id,
                        "purchase_id" => $purchase->id,
                    ])->whereIn("payment_status", ["waiting", "pending"])->update([
                        "payment_status" => "paid",
                        "updated_at" => date("Y-m-d H:i:s")
                    ]);

                    _notification_insert("purchase", $purchase->user_id, $purchase->reference_number, "Successful Payment", "Your transaction payment with ref#{$purchase->reference_number} is completed", "/profile/settings");
                    $user = User::find($purchase->user_id);
                    if($user){
                        $this->dispatch(new EmailBigQueries($user->email, "confirmed_purchase", $purchase->reference_number));
                    }

                    CLogs::insert(["message" => "E-wallet Payment transaction ref#{$purchase->reference_number} success at ".date("Y-m-d H:i:s")]);
                    return response()->json([
                        "message" => "Payment transaction success"
                    ], 200);
                }

                CLogs::insert(["message" => "E-wallet Payment transaction ref#{$purchase->reference_number} has failed at ".date("Y-m-d H:i:s")]);
                return response()->json([
                    "message" => "Payment transaction failed"
                ], 422);
            }
    
            CLogs::insert(["message" => "E-wallet Payment transaction record not found {$post_data["data"]["id"]} at ".date("Y-m-d H:i:s")]);
            return response()->json([
                "message" => "Payment transaction record not found"
            ], 422);
            
        }else{
            CLogs::insert([
                "message" => "E-wallet Unauthorized access of hook of ".date("Y-m-d H:i:s"),
                "payload" => $request->getContent(),
                "signature" => $request->header('Paymongo-Signature')
            ]);
            return response()->json([],401);
        }
    }

    function catch_card_hook(Request $request){

        if($request->intent && $request->ref){
            $purchase = Purchase::where([
                "reference_number" => $request->ref,
                "user_id" => Auth::user()->id,
                "payment_gateway" => "paymongo",
                "payment_method" => "card",
                "payment_status" => "waiting"
            ])->first();

            $client = new Client;
            $response = $client->get("https://api.paymongo.com/v1/payment_intents/{$request->intent}", [
                "headers" => [
                    "content-type" => "application/json",
                    "authorization" => "Basic {$this->paymongo_['secret']}"
                ],
            ]);
    
            $result = $response->getBody()->getContents();
            $result = json_decode($result); 
            $data = $result->data;

            $payment = $data->attributes->payments;
            foreach ($payment as $pmy) {
                
                switch ($pmy->attributes->status) {
                    case "paid":
                        if($purchase){
                            $purchase->payment_status = "paid";
                            $purchase->payment_at = date("Y-m-d H:i:s");
                            $purchase->updated_at = date("Y-m-d H:i:s");
                            $purchase->fast_status = "confirmed";

                            Purchase_Item::where([
                                "user_id" => Auth::user()->id,
                                "purchase_id" => $purchase->id,
                            ])->whereIn("payment_status", ["waiting", "pending"])->update([
                                "payment_status" => "paid",
                                "updated_at" => date("Y-m-d H:i:s")
                            ]);
                            
                            $purchase->save();

                            _notification_insert("purchase", Auth::user()->id, $request->ref, "Successful Payment", "Your transaction payment with ref#{$request->ref} is completed", "/profile/settings");
                            $this->dispatch(new EmailBigQueries(Auth::user()->email, "confirmed_purchase", $purchase->reference_number));

                            CLogs::insert(["message" => "Payment transaction ref#{$purchase->reference_number} success at ".date("Y-m-d H:i:s")]);
                            Session::flash("success", "Payment transaction recieved! Successful transaction!");
                        }else{
                            CLogs::insert([
                                "message" => "Transaction failed in payment through card! Transaction record not found",
                                "payload" => json_encode($data),
                            ]);                            
                            Session::flash("error", "Payment transaction record not found!");
                        }
                        
                        return redirect("profile/settings");
                    break;

                    case "failed":
                        $last_payment_error = $data->attributes->last_payment_error;
                        if($purchase){
                            $purchase->payment_status = "failed";
                            $purchase->updated_at = date("Y-m-d H:i:s");

                            Purchase_Item::where([
                                "user_id" => Auth::user()->id,
                                "purchase_id" => $purchase->id,
                            ])->whereIn("payment_status", ["waiting", "pending"])->update([
                                "payment_status" => "failed",
                                "updated_at" => date("Y-m-d H:i:s")
                            ]);
                            
                            $purchase->save();

                            _notification_insert("purchase", Auth::user()->id, $request->ref, "Failed Payment", "Your transaction payment with ref#{$request->ref} has failed", "/profile/settings");
                            $this->dispatch(new EmailBigQueries(Auth::user()->email, "cancelled_purchase", $purchase->reference_number));

                            CLogs::insert([
                                "message" => "Transaction failed in payment through card! {$last_payment_error->failed_message}",
                                "payload" => json_encode($data),
                                "payload_text" => json_encode($data->attributes->last_payment_error),
                            ]);

                            Session::flash("error", "Transaction failed in payment through card! {$last_payment_error->failed_message}");
                        }else{                            
                            Session::flash("error", "Payment transaction record not found!");
                        }

                        return redirect("profile/settings");
                    break;
                }
            }

            if($data->attributes->status === "awaiting_payment_method"){
                if($purchase){
                    $purchase->payment_status = "cancelled";
                    $purchase->updated_at = date("Y-m-d H:i:s");
    
                    Purchase_Item::where([
                        "user_id" => Auth::user()->id,
                        "purchase_id" => $purchase->id,
                    ])->whereIn("payment_status", ["waiting", "pending"])->update([
                        "payment_status" => "cancelled",
                        "updated_at" => date("Y-m-d H:i:s")
                    ]);
                    
                    $purchase->save();
    
                    _notification_insert("purchase", Auth::user()->id, $request->ref, "Cancelled Payment", "Your transaction payment with ref#{$request->ref} has been cancelled", "/profile/settings");
                    $this->dispatch(new EmailBigQueries(Auth::user()->email, "cancelled_purchase", $purchase->reference_number));
                    
                }

                CLogs::insert([
                    "message" => "ref#{$request->ref} 3DS Authentication with card has been cancelled!",
                    "payload" => json_encode($data),
                    "payload_text" => json_encode($data->attributes->last_payment_error),
                ]);
    
                Session::flash("info", "Authentication cancelled!");
            }else{
                CLogs::insert([
                    "message" => "Transaction failed in payment through card!",
                    "payload" => json_encode($data),
                    "payload_text" => json_encode($data->attributes->last_payment_error),
                ]);
    
                Session::flash("error", "Transaction failed in payment through card!");
            }

            
            return redirect("profile/settings");
    
        }else{
            Session::flash("info", "Payment not found!");
            return redirect("/");
        }
    }

    private function _valid_payment_hook($my_signature){
        $web_hook_client_keys = [];
        $enabled_hooks = [];

        $client = new Client;
        $response = $client->get("https://api.paymongo.com/v1/webhooks", [
            "headers" => [
                "content-type" => "application/json",
                "authorization" => "Basic {$this->paymongo_['secret']}"
            ],
        ]);
        
        if($response->getStatusCode() == 200){
            $result = $response->getBody()->getContents();
            $result = json_decode($result); 
            $data = $result->data;
            
            if(count($data) > 0){
                foreach($data as $check){
                    if($check->attributes->status == "enabled"){
                        $enabled_hooks[] = $check;
                        $web_hook_client_keys[] = hash_hmac('sha256', $my_signature, $check->attributes->secret_key);
                    }
                }
            }
        }

        return $web_hook_client_keys;
    }
}

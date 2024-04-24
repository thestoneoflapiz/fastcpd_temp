<?php

namespace App\Http\Controllers\Checkout\PayMongo;

use App\{
    User, Provider, Course,
    Purchase, Purchase_Item, My_Cart, CLogs
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Jobs\{EmailBigQueries};

use Response; 
use Session;

class CardController extends Controller 
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

        if(Auth::check()){
            $my_cart = My_Cart::where([
                "user_id" => Auth::user()->id,
                "status" => "active"
            ])->first();

            if($my_cart){

                return view("page.cart.payment_forms.card");
            }
        }

        return view("page.cart.payment_forms.failed", [
            "title" => "No items on cart",
            "message" => "Sorry! You have no items on your cart for checkout."
        ]);
    }


    function payment_process(Request $request) : JsonResponse
    {
        $CHECKOUT_AMOUNT_ = _get_checkout_items();
        $real_price = $CHECKOUT_AMOUNT_["total_amount"];
        $real_price = number_format($real_price, 2, '.', '');
        $real_price_clean = (int)str_replace(".", "", $real_price);

        if($real_price==0){
            return response()->json([
                "message" => 'No Payment found! Please refresh your browser. Thank you!'
            ], 422);
        }
        
        $reference_number = date("Y").strtoupper(uniqid());

        $client = new Client;
        $response = $client->post("https://api.paymongo.com/v1/payment_intents", [
            "headers" => [
                "content-type" => "application/json",
                "authorization" => "Basic {$this->paymongo_['secret']}"
            ],
            "json" => [
                "data" => [
                    "attributes" => [
                        "amount" => $real_price_clean,
                        "description" => "FASTCPD REFERENCE # {$reference_number}",
                        "payment_method_allowed" => ["card"],
                        "payment_method_options" => [
                            "card" => [
                                "request_three_d_secure" => "any"
                            ]
                        ],
                        "currency" => "PHP",
                    ]
                ]
            ]
        ]);

        if($response->getStatusCode() == 200){
            $result = $response->getBody()->getContents();
            $result = json_decode($result); 
            $payment_intent_data = $result->data;

            /**
             * Payment 
             * 
             */
            $card_number = str_replace(" ", "", $request->card_number);
            $name = $request->name;
            $cvc = $request->cvv;
            $email = $request->email;
            $phone = $request->phone;
            try {
                $client_pym = new Client;
                $response_pym = $client_pym->post("https://api.paymongo.com/v1/payment_methods", [
                    "headers" => [
                        "content-type" => "application/json",
                        "authorization" => "Basic {$this->paymongo_['secret']}"
                    ],
                    "json" => [
                        "data" => [
                            "attributes" => [
                                "type" => "card",
                                "details" => [
                                    "card_number" => $card_number,
                                    "exp_month" => (int)$request->expiration_month,
                                    "exp_year" => (int)$request->expiration_year,
                                    "cvc" => $cvc,
                                ],
                                "billing" => [
                                    "name" => Auth::user()->name,
                                    "email" => Auth::user()->email,
                                ]
                            ]
                        ]
                    ]
                ]);
                
                if($response_pym->getStatusCode() == 200){
                    $result_pym = $response_pym->getBody()->getContents();
                    $result_pym = json_decode($result_pym); 
                    $payment_data = $result_pym->data;
                    $payment_data->id;
    
                    /**
                     * Attach to payment intent
                     * 
                     */
                    $client_complete = new Client;
                    $response_complete = $client_complete->post("https://api.paymongo.com/v1/payment_intents/{$payment_intent_data->id}/attach", [
                        "headers" => [
                            "content-type" => "application/json",
                            "authorization" => "Basic {$this->paymongo_['secret']}"
                        ],
                        "json" => [
                            "data" => [
                                "attributes" => [
                                    "payment_method" => $payment_data->id,
                                    "return_url" => url("/hooks/pmongo/payment/card")."?intent={$payment_intent_data->id}&ref={$reference_number}" 
                                ]
                            ]
                        ]
                    ]);
                    
                    $result_complete = $response_complete->getBody()->getContents();
                    $result_complete = json_decode($result_complete); 
                    $data_complete = $result_complete->data;
    
                    $payment_status = $data_complete->attributes->status;
                    if($payment_status=="awaiting_next_action"){
                        $next_actions = $data_complete->attributes->next_action;
    
                        $purchase = new Purchase;
                        $purchase->user_id = Auth::user()->id;
                        $purchase->reference_number = $reference_number;
                        
                        $purchase->payment_client_id = $payment_data->id;
                        $purchase->payment_client_key = $payment_intent_data->attributes->client_key;
                        $purchase->payment_gateway = "paymongo";
                        $purchase->payment_method = "card";
                        $purchase->payment_status = "waiting";
                        $purchase->payment_notes = $next_actions->redirect->url;
                        
                        $purchase->vouchers = count($CHECKOUT_AMOUNT_["vouchers"]) > 0 ? json_encode($CHECKOUT_AMOUNT_["vouchers"]) : null;
                        $purchase->total_discount = $CHECKOUT_AMOUNT_["total_discount_percent"] ?? null;
                        $purchase->total_amount = $CHECKOUT_AMOUNT_["total_amount"];

                        $purchase->created_at = date("Y-m-d H:i:s");
                        $purchase->updated_at = date("Y-m-d H:i:s");
                        $purchase->fast_status = "waiting";
                        $purchase->save();
                        
                        $this->move_to_checkout_mycart($CHECKOUT_AMOUNT_["my_cart"], $purchase, "waiting");
    
                        return response()->json([
                            "message" => "Please wait... redirecting to authentication",
                            "redirect" =>  true,
                            "redirect_link" => $next_actions->redirect->url,
                        ], 200);
                    }else{
                        $payments = $data_complete->attributes->payments;
                        foreach ($payments as $pmy) {
                            switch ($pmy->attributes->status) {
                                case "paid":
                                    $purchase = new Purchase;
                                    $purchase->user_id = Auth::user()->id;
                                    $purchase->reference_number = $reference_number;

                                    $purchase->payment_client_id = $payment_data->id;
                                    $purchase->payment_client_key = $payment_intent_data->attributes->client_key;
                                    $purchase->payment_gateway = "paymongo";
                                    $purchase->payment_method = "card";
                                    $purchase->payment_at = date("Y-m-d H:i:s");
                                    $purchase->payment_status = "paid";
                                    $purchase->fast_status = "confirmed";

                                    $purchase->vouchers = count($CHECKOUT_AMOUNT_["vouchers"]) > 0 ? json_encode($CHECKOUT_AMOUNT_["vouchers"]) : null;
                                    $purchase->total_discount = $CHECKOUT_AMOUNT_["total_discount_percent"] ?? null;
                                    $purchase->total_amount = $CHECKOUT_AMOUNT_["total_amount"];

                                    $purchase->created_at = date("Y-m-d H:i:s");
                                    $purchase->updated_at = date("Y-m-d H:i:s");
                                    $purchase->save();
                                    $this->move_to_checkout_mycart($CHECKOUT_AMOUNT_["my_cart"], $purchase, "paid");
    
                                    _notification_insert("purchase", Auth::user()->id, $reference_number, "Successful Payment", "Your transaction payment with ref#{$reference_number} is completed", "/profile/settings");
                                    $this->dispatch(new EmailBigQueries(Auth::user()->email, "confirmed_purchase", $reference_number));
                                    return response()->json([
                                        "message" => "Successful transaction! Redirecting you to purchase overview in a few seconds...",
                                        "redirect" =>  true,
                                    ], 200);
        
                                break;
                            }
                        }
    
                        CLogs::insert([
                            "message" => "Transaction failed in Paymongo Payment through card! Attachment to intent ".date("Y-m-d"),
                            "payload" => json_encode($data_complete),
                            "payload_text" => json_encode($data_complete->attributes->last_payment_error),
                        ]);
            
                        return response()->json([
                            "message" => 'Transaction failed! Please check your purchase overview to review your transactions, or, try contacting us through <b><a target="_blank" href="https://facebook.com/fastcpd">HERE</a></b>'
                        ], 422);
                    }
                    
                }
    
                CLogs::insert([
                    "message" => "Transaction failed in Paymongo Payment through card! ".date("Y-m-d"),
                    "payload" => json_encode($payment_intent_data),
                    "payload_text" => $payment_intent_data->id,
                ]);
    
                return response()->json([
                    "message" => 'Transaction failed! Please check your purchase overview to review your transactions, or, try contacting us through <b><a target="_blank" href="https://facebook.com/fastcpd">HERE</a></b>'
                ], 422);
            } catch (ClientException $err) {
                $response = $err->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                $responseBodyAsArray = json_decode($responseBodyAsString); 
                $errorArray = $responseBodyAsArray->errors;

                CLogs::insert([
                    "message" => "Transaction failed in Paymongo Payment through card! ".date("Y-m-d"),
                    "payload" => json_encode($responseBodyAsArray),
                    "payload_text" => json_encode([
                        "amount" => $real_price_clean,
                        "description" => "FASTCPD REFERENCE # {$reference_number}",
                        "payment_method_allowed" => ["card"],
                        "payment_method_options" => [
                            "card" => [
                                "request_three_d_secure" => "any"
                            ]
                        ],
                        "currency" => "PHP",
                        "PAYMENTintentID" => $payment_intent_data->id,
                        "PAYMENTmethodDATA" => [
                            "attributes" => [
                                "type" => "card",
                                "details" => [
                                    "card_number" => $card_number,
                                    "exp_month" => (int)$request->expiration_month,
                                    "exp_year" => (int)$request->expiration_year,
                                    "cvc" => $cvc,
                                ],
                                "billing" => [
                                    "name" => Auth::user()->name,
                                    "email" => Auth::user()->email,
                                ]
                            ]
                        ]
                    ])
                ]);

                foreach ($errorArray as $key => $error_) {
                    switch ($error_->code) {
                        case 'parameter_format_invalid':

                            return response()->json([
                                "message" => "Transaction failed! {$error_->detail}"
                            ], 422);
                        break;

                        case 'resource_failed_state':

                            if(in_array($error_->sub_code, ["fraudulent", "processor_blocked", "lost_card", "stolen_card", "blocked"])){
                                return response()->json([
                                    "message" => 'Transaction in Payment intention failed! Please contact your card issuing bank for further details.'
                                ], 422);
                            }

                            return response()->json([
                                "message" => "Transaction failed! {$error_->detail}"
                            ], 422);
                        break;
                        
                        default:
                            # code...
                            break;
                    }
                }
            }
        }
        
        return response()->json([
            "message" => 'Transaction in Payment intention failed! Please check your purchase overview to review your transactions, or, try contacting us through <b><a target="_blank" href="https://facebook.com/fastcpd">HERE</a></b>'
        ], 422);
    }

    function move_to_checkout_mycart($mycart, $purchase, $status){
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

                "credited_cpd_units" => $item["accreditation"] ? json_encode($item["accreditation"]) :  null,
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
                
                "payment_status" => $status,
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
}
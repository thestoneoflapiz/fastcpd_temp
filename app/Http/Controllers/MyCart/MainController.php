<?php

namespace App\Http\Controllers\MyCart;

use App\{
    User, Provider,
    Purchase, Purchase_Item, My_Cart,
    Profession, Voucher,

    Course,
    Webinar, Webinar_Series, Webinar_Session,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class MainController extends Controller
{

    /**
     * index page of cart
     * 
     */
    function index() {
        $data = [];
        
        $my_cart = _get_my_cart_items();
        $data["my_cart"] = $my_cart;
        $data["total_items"] = count($my_cart);

        $data["units"] = [];
        $data["total_price"] = 0;
        $data["total_discounted_price"] = 0;
        $total_add_to_discount_total = 0;
        $data["vouchers"] = [];
        foreach ($my_cart as $mc) {
            if($mc["accreditation"]){
                foreach ($mc["accreditation"] as $acc) {
                    $data["units"][$acc->id]["title"] = $acc->title;
                    $data["units"][$acc->id]["units"][] = $acc->units;
                }
            }

            $data["total_price"] += $mc["price"];
            $data["total_discounted_price"] += $mc["discounted_price"] ?? 0;

            if($mc["discount"]==null){
                $total_add_to_discount_total += $mc["price"];
            }

            if($mc["voucher"]){
                if(!in_array($mc["voucher"], $data["vouchers"])){
                    $data["vouchers"][] = $mc["voucher"];
                }
            }
        }

        $data["total_discounted_price"] = $data["total_discounted_price"] ? ($data["total_discounted_price"] + $total_add_to_discount_total) : 0;
		return view('page/cart/shopping_cart', $data);
    }
    
    /**
     * 
     * add to cart
     * 
     */
    function add(Request $request) : JsonResponse
    {
        $type = $request->type;
        $data_id = $request->data_id;

        $cart_items = [];
        $total_price = 0;
        $total_discounted_price = 0;

        if(Auth::check()){
            $_purchase_status =  _has_purchased_item($type, $data_id);
            if($_purchase_status["purchased"]){
                return response()->json(["message" => $_purchase_status["message"] ?? "Unable to add to cart! Please check your cart or <b>\"My Items\"</b>", "button"=>[
                    "status" => "info",
                    "url" => $_purchase_status["url"],
                    "label" => $_purchase_status["goto"] == "live" ? "Go to Live" : "Go to Overview",
                ]], 422);
            }
        }

        if($type=="course"){
            $data_record = Course::select(
                "id as data_id", "title", "url", 
                "price", "accreditation", "provider_id", 
                "course_poster as poster", 
                "session_start", "session_end", "language",
                "published_at"
            )->where([
                "prc_status" => "approved",
                "deleted_at" => null,
                "id" => $data_id,
            ])->whereIn("fast_cpd_status", ["published", "live"])->first();
        }else{
            $data_record = Webinar::select(
                "id as data_id", "title", "url", 
                "prices", "accreditation", "provider_id", 
                "webinar_poster as poster", "language",
                "offering_units", "event",
                "published_at"
            )->where([
                "deleted_at" => null,
                "id" => $data_id,
            ])->whereIn("fast_cpd_status", ["published", "live"])->first();
        }

        if($data_record){

            $data_record = $data_record->toArray();
            $data_record["type"] = $type;
            $data_record["poster"] = _get_image($type, $data_id)["small"];

            if($type=="webinar"){
                if($request->schedule_type=="series"){
                    if(!_purchase_series($data_record["data_id"], $request->schedule_id)){
                        return response()->json(["message" => "This item is unavailable for purchase! Series has already started", "button"=>[
                            "status" => "success",
                            "label" => "Add to Cart",
                        ]], 422);
                    };
                }

                $data_record["offering_type"] = $request->offering_type;
                $data_record["schedule_type"] = $request->schedule_type;
                $data_record["schedule_id"] = $request->schedule_id;

                $prices = json_decode($data_record["prices"]);
                if($request->offering_type=="with"){
                    $data_record["price"] = $prices->with;
                }else{
                    $data_record["price"] = $prices->without ?? 0;
                }
            }
            
            if($type == "webinar" && ($request->has("discount") && $request->discount)){
                $discount = _get_applied_discount($type, $data_id, $request->discount);
            }else{
                $discount = _get_fast_discount($type, $data_id);
            }

            if($discount){
                $data_record['discount'] = $discount->discount;
                $data_record['voucher'] = $discount->voucher_code;
                $data_record['channel'] = $discount->channel;

                $dm = ($data_record["discount"] / 100) * $data_record["price"];
                $dp = $data_record["price"] - $dm;
                $data_record['discounted_price'] = $dp;
                $data_record['total_amount'] = $dp;
            }else{
                $data_record['discounted_price'] = null;
                $data_record['discount'] = null;
                $data_record['voucher'] = null;
                $data_record['channel'] = "fast_promo";
                $data_record['total_amount'] = $data_record["price"];
            }
            
            if($data_record["accreditation"]){
                if($type=="webinar" && $request->offering_type=="without"){
                    $data_record["accreditation"] = null;
                }else{
                    $accreditation = array_map(function($acc){
                        $proffesion = Profession::select("title")->find($acc->id);
                        $acc->title = $proffesion->title;
        
                        return $acc;
                    }, $data_record["accreditation"] ? json_decode($data_record["accreditation"]) : []);
                    $data_record["accreditation"] = $accreditation;
                }
            }

            if(Auth::check()){
                $db_my_cart = My_Cart::where([
                    "user_id" => Auth::user()->id,
                    "type" => $type,
                    "data_id" => $data_id,
                    "status" => "active",
                ])->first();

                if($db_my_cart){
                }else{
                    My_Cart::insert([
                        "user_id" => Auth::user()->id,
                        "type" => $type,
                        "data_id" => $data_id,
                        "accreditation" => $data_record["accreditation"] ? json_encode($data_record["accreditation"]) : null,
                        "price" => $data_record["price"],
                        "discounted_price" => $data_record["discounted_price"] ?? null,
                        "discount" => $data_record["discount"] ?? null,
                        "voucher" => $data_record["voucher"] ?? null,
                        "channel" => $data_record["channel"] ?? "fast_promo",
                        "total_amount" => $data_record["total_amount"] ?? null,

                        "offering_type" => $data_record["offering_type"] ?? null,
                        "schedule_type" => $data_record["schedule_type"] ?? null,
                        "schedule_id" => $data_record["schedule_id"] ?? null,

                        "status" => "active",
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s"),
                    ]);

                }

                $db_my_cart = My_Cart::where([
                    "user_id" => Auth::user()->id,
                    "status" => "active",
                ])->get();

                foreach($db_my_cart as $dmc){
                    $total_price += $dmc->price;
                    $total_discounted_price += $dmc->discounted_price;
                }

                $total_items = $db_my_cart->count();
                Session::forget("my_cart_in_session");
            }else{
                if(Session::has("my_cart_in_session")){
                    $found = false;
    
                    $session_cart_items = Session::get("my_cart_in_session");
                    foreach($session_cart_items as $sci){
                        if($sci["data_id"] == $data_record["data_id"] && $sci["type"] == $data_record["type"]){
                            $found = true;
                        }else{
                            $cart_items[] = $sci;
                        }

                        $total_price += $sci["price"];
                        $total_discounted_price += $sci["discounted_price"];
                    }
                    
                    if(!$found){
                        $total_price += $data_record["price"];
                        $total_discounted_price += $data_record["discounted_price"];

                        $cart_items = array_merge($cart_items, [$data_record]);
                    }else{
                        return response()->json(["message" => "This item is already added to your cart!", 
                        "data" => $data_record,
                        "total" => [
                            "price" => $total_price,
                            "discounted_price" => $total_discounted_price,
                            "items" => count(Session::get("my_cart_in_session"))
                        ],
                        "button" =>[
                            "status" => null,
                            "link" => "/cart",
                            "label" => "Go to Cart",
                        ]], 422);
                    }
    
                    Session::put("my_cart_in_session", $cart_items);
                }else{
                    /**     
                     * session does not exist yet
                     * 
                     */
                    $cart_items = $data_record;
                    $total_price += $data_record["price"];
                    $total_discounted_price += $data_record["discounted_price"];

                    Session::push("my_cart_in_session", $cart_items);
                }

                $total_items = count(Session::get("my_cart_in_session"));
            }

            return response()->json([
                "data" => $data_record,
                "total" => [
                    "price" => $total_price,
                    "discounted_price" => $total_discounted_price,
                    "items" => $total_items,
                ],
                "button"=>[
                "status" => null,
                "link" => "/cart",
                "label" => "Go to Cart",
            ]], 200);
        }

        return response()->json(["message" => "This item is unavailable for purchase!", "button"=>[
            "status" => "success",
            "label" => "Add to Cart",
        ]], 422);
    }

    /**
     * 
     * remove to cart
     * 
     */
    function remove(Request $request) : JsonResponse
    {
        $type = $request->type;
        $data_id = $request->data_id;

        $cart_items = [];
        $total_price = 0;
        
        if(Auth::check()){
            My_Cart::where([
                "type" => $type,
                "data_id" => $data_id,
                "user_id" => Auth::user()->id,
                "status" => "active"
            ])->delete();

            Session::forget("my_cart_in_session");
            $db_my_cart = My_Cart::select(
                "type", "data_id", "price", "discounted_price", "voucher", "discount", "accreditation", "total_amount",
                "offering_type", "schedule_type", "schedule_id"
            )->where([
                "user_id" => Auth::user()->id,
                "status" => "active"
            ])->get();

            return response()->json([
                "total" => $this->create_cart_array_DB($db_my_cart),
            ]);
        }else{
            $session_cart_items = Session::has("my_cart_in_session") ? Session::get("my_cart_in_session") : [];
            $session_cart_items = array_filter($session_cart_items, function($sci) use($type, $data_id){
                if($data_id==$sci["data_id"] && $type==$sci["type"]){
                }else{
                    return $sci;
                }
            });

            Session::put("my_cart_in_session", $session_cart_items);
            return response()->json([
                "total" => $this->create_cart_array_SESSION(),
            ]);
        }

        return response()->json([], 422);
    }

    function voucher_add(Request $request) : JsonResponse
    {
        $applied = 0;
        $voucher_code = $request->voucher_code;
        $voucher = Voucher::where([
            "voucher_code" => $voucher_code,
            "status" => "active",
        ]);
        
        if(Auth::check()){
            $voucher = $voucher->first();
            
		}else{
            $voucher = $voucher->whereIn("type", ["manual_apply", "auto_applied"])->first();
            
        }
        
        if($voucher){
            if(Auth::check()){

                Session::forget("my_cart_in_session");
                $db_my_cart = My_Cart::where([
                    "user_id" => Auth::user()->id,
                    "status" => "active",
                ])->get();

                /**
                 * update added voucher
                 * 
                 */
                foreach ($db_my_cart as $dmc) {
                    
                    if($voucher->channel == "fast_promo"){
                        /**
                         * check if provider is affiliated
                         */
                        if($dmc->type=="course"){
                            $data_record = Course::select("provider_id")->find($dmc->data_id);
                        }else{
                            $data_record = Webinar::select("provider_id")->find($dmc->data_id);
                        }

                        if($voucher->type=="rc_once_applied"){
                            if(!_referer_voucher($voucher_code)){
                                return response()->json([
                                    "message" => "This voucher is proprietary and for the exclusive FastCPD referer only"
                                ], 422);
                            }
                            
                            if($referer_voucher = _referer_discount($voucher_code)){
                                if($referer_voucher->discount==0){
                                    return response()->json([
                                        "message" => "Unable to redeem rewards! Please check your status at Account Profile & Settings > <b>Referral Code</b>."
                                    ], 422);
                                }

                                if($data_record){
                                    $provider = Provider::select("allow_marketing")->find($data_record->provider_id);
                                    if($provider && $provider->allow_marketing == 1){
                                        
                                        if($referer_voucher->discount > $dmc->discount){
                                            $dmc->voucher = $referer_voucher->voucher_code;
                                            $dmc->discount = $referer_voucher->discount;
                                            $dmc->channel = $referer_voucher->channel;
    
                                            $dmc->price = $dmc->price;
    
                                            $discount_value = ($referer_voucher->discount/100) * $dmc->price;
                                            $discounted_price = $dmc->price - $discount_value;
                                            $dmc->discounted_price = $discounted_price;
                                            $dmc->total_amount = $discounted_price;
    
                                            $dmc->save();
                                            $applied+=1;
                                        }
                                    }
                                } 
                            }else{

                                return response()->json([
                                    "message" => "Invalid referer voucher! It's either expired, or blocked."
                                ], 422);
                            }
                        }else{
                            if($data_record){
                                $provider = Provider::select("allow_marketing")->find($data_record->provider_id);
                                if($provider && $provider->allow_marketing == 1){
                                    
                                    
                                    if($voucher->discount > $dmc->discount){
                                        $dmc->voucher = $voucher->voucher_code;
                                        $dmc->discount = $voucher->discount;
                                        $dmc->channel = $voucher->channel;

                                        $dmc->price = $dmc->price;

                                        $discount_value = ($voucher->discount/100) * $dmc->price;
                                        $discounted_price = $dmc->price - $discount_value;
                                        $dmc->discounted_price = $discounted_price;
                                        $dmc->total_amount = $discounted_price;

                                        $dmc->save();
                                        $applied+=1;
                                    }
                                }
                            }
                        }
                    }elseif($voucher->channel == "promoter_promo"){

                        /**
                         * check if provider is affiliated
                         */
                        if($dmc->type=="course"){
                            $data_record = Course::select("provider_id")->find($dmc->data_id);
                        }else{
                            $data_record = Webinar::select("provider_id")->find($dmc->data_id);
                        }

                        if($data_record){
                            $provider = Provider::select("allow_marketing")->find($data_record->provider_id);
                            if($provider && $provider->allow_marketing == 1){
                                $dmc->voucher = $voucher->voucher_code;
                                $dmc->discount = $voucher->discount;
                                $dmc->channel = $voucher->channel;

                                $dmc->price = $dmc->price;

                                $discount_value = ($voucher->discount/100) * $dmc->price;
                                $discounted_price = $dmc->price - $discount_value;
                                $dmc->discounted_price = $discounted_price;
                                $dmc->total_amount = $discounted_price;

                                $dmc->save();
                                $applied+=1;
                            }
                        }
                    }elseif($voucher->channel == "provider_promo"){

                        if($voucher->data_id){
                            if(json_decode($voucher->data_id)->courses && $dmc->type == "course"){
                                $courses = json_decode($voucher->data_id)->courses;
                                if(in_array($dmc->data_id, $courses)){
                                    // if($voucher->discount > $dmc->discount){
                                        $dmc->voucher = $voucher->voucher_code;
                                        $dmc->discount = $voucher->discount;
                                        $dmc->channel = $voucher->channel;

                                        $dmc->price = $dmc->price;

                                        $discount_value = ($voucher->discount/100) * $dmc->price;
                                        $discounted_price = $dmc->price - $discount_value;
                                        $dmc->discounted_price = $discounted_price;
                                        $dmc->total_amount = $discounted_price;
                                        
                                        $dmc->save();
                                        $applied+=1;
                                    // }
                                }
                            }

                            if(json_decode($voucher->data_id)->webinars && $dmc->type == "webinar"){
                                $webinars = json_decode($voucher->data_id)->webinars;

                                if(in_array($dmc->data_id, $webinars)){
                                    // if($voucher->discount > $dmc->discount){
                                        $dmc->voucher = $voucher->voucher_code;
                                        $dmc->discount = $voucher->discount;
                                        $dmc->channel = $voucher->channel;

                                        $dmc->price = $dmc->price;
    
                                        $discount_value = ($voucher->discount/100) * $dmc->price;
                                        $discounted_price = $dmc->price - $discount_value;
                                        $dmc->discounted_price = $discounted_price;
                                        $dmc->total_amount = $discounted_price;
                                        
                                        $dmc->save();
                                        $applied+=1;
                                    // }
                                }
                            }
                        }
                    }
                }

                if($applied==0){
                    if($voucher->type=="auto_applied_when_loggedin" && Auth::check()){
                        return response()->json([
                            "message" => "Voucher {$voucher_code} with {$voucher->discount}% not applied. Discount is lesser than current vouchers"
                        ], 422);
                    }

                    if($voucher->provider_id==null){
                        return response()->json([
                            "message" => "Voucher {$voucher_code} with {$voucher->discount}% not applied. Discount is lesser than current vouchers"
                        ], 422);
                    }

                    return response()->json([
                        "message" => "Voucher code not applicable on any items on cart!"
                    ], 422);
                }

                return response()->json([
                    "total" => $this->create_cart_array_DB($db_my_cart),
                ]);
            }else{

                $mcis = Session::get("my_cart_in_session");  
                $new_mcis = [];
                foreach ($mcis as $my) {

                    if($voucher->channel == "fast_promo" ){
                        if($voucher->type=="rc_once_applied"){
                            if(!_referer_voucher($voucher_code)){
                                return response()->json([
                                    "message" => "This voucher is proprietary and for the exclusive FastCPD referer only"
                                ], 422);
                            }
                            // rc_once_applied
                        }else{
                            $provider = Provider::select("allow_marketing")->find($my["provider_id"]);
                            if($provider && $provider->allow_marketing == 1){
                                if($voucher->discount > $my["discount"]){
                                    $my["voucher"] = $voucher->voucher_code;
                                    $my["discount"] = $voucher->discount;
                                    $my["channel"] = $voucher->channel;
            
                                    $dm = ($my["discount"] / 100) * $my["price"];
                                    $dp = $my["price"] - $dm;
                                    $my["discounted_price"] = $dp;
                                    $my["total_amount"] = $dp;
                                    $applied+=1;
                                }
                            }
                        }
                    }else if($voucher->channel == "promoter_promo" ){

                        $provider = Provider::select("allow_marketing")->find($my["provider_id"]);
                        if($provider && $provider->allow_marketing == 1){
                            $my["voucher"] = $voucher->voucher_code;
                            $my["discount"] = $voucher->discount;
                            $my["channel"] = $voucher->channel;
    
                            $dm = ($my["discount"] / 100) * $my["price"];
                            $dp = $my["price"] - $dm;
                            $my["discounted_price"] = $dp;
                            $my["total_amount"] = $dp;
                            $applied+=1;
                        }
                    }else{
                        if($voucher->data_id){
                            if(json_decode($voucher->data_id)->courses && $my["type"] == "course"){
                                $courses = json_decode($voucher->data_id)->courses;
                                if(in_array($my["data_id"], $courses)){
                                    // if($voucher->discount > $my["discount"]){
                                        $my["voucher"] = $voucher->voucher_code;
                                        $my["discount"] = $voucher->discount;
                                        $my["channel"] = $voucher->channel;
                
                                        $dm = ($my["discount"] / 100) * $my["price"];
                                        $dp = $my["price"] - $dm;
                                        $my["discounted_price"] = $dp;
                                        $my["total_amount"] = $dp;
                                        $applied+=1;
                                    // }
                                }
                            }

                            if(json_decode($voucher->data_id)->webinars && $my["type"] == "webinar"){
                                $webinars = json_decode($voucher->data_id)->webinars;
                                if(in_array($my["data_id"], $webinars)){
                                    // if($voucher->discount > $my["discount"]){
                                        $my["voucher"] = $voucher->voucher_code;
                                        $my["discount"] = $voucher->discount;
                                        $my["channel"] = $voucher->channel;
                
                                        $dm = ($my["discount"] / 100) * $my["price"];
                                        $dp = $my["price"] - $dm;
                                        $my["discounted_price"] = $dp;
                                        $my["total_amount"] = $dp;
                                        $applied+=1;
                                    // }
                                }
                            }
                        }
                    }

                    $new_mcis[] = $my;
                }

                if($applied==0){
                    if($voucher->type=="auto_applied_when_loggedin" && Auth::check()){
                        return response()->json([
                            "message" => "Voucher {$voucher_code} with {$voucher->discount}% not applied. Discount is lesser than current vouchers"
                        ], 422);
                    }

                    if($voucher->provider_id==null){
                        return response()->json([
                            "message" => "Voucher {$voucher_code} with {$voucher->discount}% not applied. Discount is lesser than current vouchers"
                        ], 422);
                    }

                    return response()->json([
                        "message" => "Voucher code not applicable on any items on cart!"
                    ], 422);
                }

                if(count($new_mcis)>0){
                    Session::put("my_cart_in_session", $new_mcis);
                }

                return response()->json([
                    "total" => $this->create_cart_array_SESSION(),
                ]);
            }

            return response()->json(null, 200);
        }

        return response()->json([
            "message" => "Invalid voucher! It's either expired, or doesn't exist."
        ], 422);
    }

    /**
     * not yet done
     * 
     */
    function voucher_remove(Request $request) : JsonResponse
    {
        $voucher_code = $request->voucher_code;
        $voucher = Voucher::where([
            "voucher_code" => $voucher_code,
            "status" => "active"
        ])->first();

        if($voucher){
            if($voucher->channel == "fast_promo"){
                if($voucher->type=="auto_applied"){
                    return response()->json(null, 200);
                }

                if(Auth::check() && $voucher->type == "auto_applied_when_loggedin"){
                    return response()->json(null, 200);
                }
            }
        }

        if(Auth::check()){
            Session::forget("my_cart_in_session");
            $db_my_cart = My_Cart::where([
                "user_id" => Auth::user()->id,
                "voucher" => $voucher_code,
                "status" => "active",
            ])->get();

            foreach($db_my_cart as $dmc){
                $fast_discount = _get_fast_discount($dmc->type, $dmc->data_id);
                if($fast_discount){
                    $dmc->channel = $fast_discount->channel;
                    $dmc->voucher = $fast_discount->voucher_code;
                    $dmc->discount = $fast_discount->discount;

                    $discount_value = ($fast_discount->discount/100) * $dmc->price;
                    $dmc->discounted_price = $dmc->price - $discount_value;
                    $dmc->total_amount = $dmc->price - $discount_value;
                }else{
                    $dmc->channel = "fast_promo";
                    $dmc->voucher = null;
                    $dmc->discount = null;
                    $dmc->discounted_price = 0;
                    $dmc->total_amount = $dmc->price;
                }
                
                $dmc->save();
            }

            /**
             * ui/uix new change
             * 
             */
            $db_my_cart = My_Cart::where([
                "user_id" => Auth::user()->id,
                "status" => "active",
            ])->get();

            return response()->json([
                "total" => $this->create_cart_array_DB($db_my_cart),
            ]);
        }else{
            /**
             * clean session remove voucher, discount, and change channel
             * 
             */

            $mcis = Session::get("my_cart_in_session");
            $mcis = array_map(function($my){
                $fast_discount = _get_fast_discount($my["type"], $my["data_id"]);
                if($fast_discount){
                    $my["channel"] = $fast_discount->channel;
                    $my["voucher"] = $fast_discount->voucher_code;
                    $my["discount"] = $fast_discount->discount;

                    $dm = ($fast_discount->discount / 100) * $my["price"];
                    $dp = $my["price"] - $dm;
                    $my["discounted_price"] = $dp;
                    $my["total_amount"] = $dp;
                }else{
                    $my["channel"] = "fast_promo";
                    $my["voucher"] = null;
                    $my["discount"] = null;
                    $my["discounted_price"] = 0;
                    $my["total_amount"] = $my["price"];
                }
                return $my;
            }, $mcis);

            Session::put("my_cart_in_session", $mcis);
            return response()->json([
                "total" => $this->create_cart_array_SESSION(),
            ]);
        }

        return response()->json([
            "message" => "Something went wrong! Please refresh your browser"
        ], 422);
    }

    function create_cart_array_DB($db_my_cart){
        
        $data = [
            "units" => [],
            "vouchers" => [],
            "price" => 0,
            "discounted_price" => 0,
            "discount" => 0,
            "total_amount" => 0,
            "items" => $db_my_cart->count()
        ];

        if($db_my_cart->count() > 0){
            foreach ($db_my_cart as $dmc) {

                if($dmc->type=="course"){
                    $data_record = Course::select("title", "url")->find($dmc->data_id);  
                }else{
                    $data_record = Webinar::select("title", "url")->find($dmc->data_id);  
                }

                $dmc->title = $data_record->title;  
                $dmc->url = $data_record->url;  
                $dmc->poster = _get_image($dmc->type, $dmc->data_id)["small"];  

                if($dmc["accreditation"]){
                    $accreditation = json_decode($dmc["accreditation"]);
                    foreach ($accreditation as $acc) {
                        $data["units"][$acc->id]["title"] = $acc->title;
                        $data["units"][$acc->id]["units"][] = $acc->units;
                    }
                }
    
                if($dmc["voucher"]){
                    if(!in_array($dmc["voucher"], $data["vouchers"])){
                        $data["vouchers"][] = $dmc["voucher"];
                    }
                }

                $data["price"] += $dmc["price"];
                $data["total_amount"] += $dmc["total_amount"];
                $data['discounted_price'] += $dmc['discounted_price'];
            }
        }

        $data["units"] = array_values($data["units"]);
        $data["data"] = $db_my_cart;
        $discount_value = $data["discounted_price"] > 0 ? $data["price"] - $data["discounted_price"] : 0;
        $data["discount"] = $data["discounted_price"] > 0 ? (($discount_value / $data["price"]) * 100) : 0;
    
        return $data;
    }
    
    function create_cart_array_SESSION(){
        $data = [
            "units" => [],
            "vouchers" => [],
            "price" => 0,
            "discounted_price" => 0,
            "discount" => 0,
            "total_amount" => 0,
            "items" => count(Session::get("my_cart_in_session"))
        ];

        if($data["items"] > 0){
            foreach (Session::get("my_cart_in_session") as $mcis) {

                if($mcis["type"]=="course"){
                    $data_record = Course::select("title", "url")->find($mcis["data_id"]);  
                }else{
                    $data_record = Webinar::select("title", "url")->find($mcis["data_id"]);    
                }
                $mcis["title"] = $data_record->title;  
                $mcis["url"] = $data_record->url;  
                $mcis["poster"] = _get_image($mcis["type"], $mcis["data_id"])["small"];  

                if($mcis["accreditation"]){
                    $accreditation = $mcis["accreditation"];
                    foreach ($accreditation as $acc) {
                        $data["units"][$acc->id]["title"] = $acc->title;
                        $data["units"][$acc->id]["units"][] = $acc->units;
                    }
                }

                if($mcis["voucher"]){
                    if(!in_array($mcis["voucher"], $data["vouchers"])){
                        $data["vouchers"][] = $mcis["voucher"];
                    }
                }

                $data["price"] += $mcis["price"];
                $data["total_amount"] += $mcis["total_amount"];
                $data['discounted_price'] += $mcis['discounted_price'];
            }
        }
        
        $data["units"] = array_values($data["units"]);
        $data["data"] = array_values(Session::get("my_cart_in_session"));
        $discount_value = $data["discounted_price"] > 0 ? $data["price"] - $data["discounted_price"] : 0;
        $data["discount"] = $data["discounted_price"] > 0 ? (($discount_value / $data["price"]) * 100) : 0;

        return $data;
    }
}
<?php

use App\{
    User, Logs, Info, 
    Profession, Top_Profession,
    Provider, Co_Provider, Instructor, 
    Course, Profile_Requests, Review, 
    Provider_Permission,
    Section, Article, Video, Quiz,
    Quiz_Item, Quiz_Score,
    Course_Progress,
    Instructor_Permissions, Handout,
    Instructor_Resume,
    Course_Rating, CoursePerformance, System_Rating_Report, 
    
    My_Cart, Voucher, Notification,
    Purchase, Purchase_Item,
    Image_Intervention,

    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Instructor_Permission, Webinar_Progress,
    Webinar_Performance, Webinar_Rating, Webinar_Attendance, 
    
    Revenue_Sharing,
};
use Illuminate\Support\Facades\{Auth};

if (! function_exists('_has_reached_limit')) {

    function _has_reached_limit($type, $data_id)
    {
        if($type=="course"){
            $data_record = Course::select("target_number_students")->find($data_id);
            if($data_record){
                $limit = $data_record->target_number_students;
                if($limit>0){
                    $existing_purchases = Purchase_Item::where([
                        "type" => $type,
                        "data_id" => $data_id,
                    ])->whereIn("payment_status", [
                        "paid", "waiting", "pending"
                    ])->get();

                    if($existing_purchases && ($existing_purchases->count() >= $limit)){
                        return true;
                    }
                }
            }
        }else{
            $data_record = Webinar::select("id", "event", "target_number_students")->find($data_id);
            if($data_record){
                $limit = $data_record->target_number_students;
                if($limit>0){
                    if($data_record->event == "day"){

                        $sessions = Webinar_Session::select("id as schedule_id")->where([
                            "webinar_id" => $data_record->id,
                        ])->get();

                        $no_slots = true;
                        foreach ($sessions as $key => $session) {
                            $existing_purchases = Purchase_Item::select("id")->where([
                                "type" => $type,
                                "data_id" => $data_id,
                                "schedule_id" => $session->schedule_id,
                                "schedule_type" => $data_record->event,
                            ])->whereIn("payment_status", [
                                "paid", "waiting", "pending"
                            ])->get();
        
                            if($existing_purchases && ($existing_purchases->count() >= $limit)){
                            }else{
                                $no_slots = false;
                            }
                        }

                        return $no_slots;
                    }else{
                        $series = Webinar_Series::select("id as schedule_id")->where("webinar_id", "=", $data_record->id)->get();
                        $no_slots = true;
                        foreach ($series as $key => $srs) {
                            $existing_purchases = Purchase_Item::select("id")->where([
                                "type" => $type,
                                "data_id" => $data_id,
                                "schedule_id" => $srs->schedule_id,
                                "schedule_type" => $data_record->event,
                            ])->whereIn("payment_status", [
                                "paid", "waiting", "pending"
                            ])->get();
        
                            if($existing_purchases && ($existing_purchases->count() >= $limit)){
                            }else{
                                $no_slots = false;
                            }
                        }

                        return $no_slots;
                    }
                }
            }
        } 

        return false;
    }
}

/**
 * My Cart
 * 
 * 
 */

if(! function_exists('_get_session_cart')){
    function _get_session_cart($type, $data_id){

        $_has_reached_limit = _has_reached_limit($type, $data_id);
        if($_has_reached_limit){
            return [
                "status" => "warning",
                "link" => "javascript:;",
                "label" => "Out of Slots"
            ];
        }

        if(Auth::check()){
            $db_my_cart = My_Cart::select("data_id")->where([
                "user_id" => Auth::user()->id,
                "type" => $type,
                "data_id" => $data_id,
                "status" => "active"
            ])->first();

            if($db_my_cart){
                
                return [
                    "status" => "danger",
                    "link" => "/cart",
                    "label" => "Go to Cart"
                ];
            }

            return [
                "status" => "success",
                "link" => null,
                "label" => "Add to Cart"
            ];

        }else{
            $session_my_cart = Session::has("my_cart_in_session") ? Session::get("my_cart_in_session") : [];
            $session_my_cart = array_filter($session_my_cart, function($sci) use($type, $data_id){
                if($sci["data_id"] == $data_id && $sci["type"] == $type){
                    return $sci;
                }
            });

            if(count($session_my_cart)>0){
                return [
                    "status" => "danger",
                    "link" => "/cart",
                    "label" => "Go to Cart"
                ];
            }

            return [
                "status" => "success",
                "link" => null,
                "label" => "Add to Cart"
            ];
        }
    }
}

if(! function_exists('_save_session_cart_to_db')){
    function _save_session_cart_to_db(){
        if(Auth::check()){
            $db_my_cart = My_Cart::select("type","data_id")->where([
                "user_id" => Auth::user()->id,
                "status" => "active"
            ])->get();

            $session_my_cart = Session::has("my_cart_in_session") ? Session::get("my_cart_in_session") : [];
            
            if($db_my_cart->count() == 0){ 
                foreach($session_my_cart as $sci){
                    $has_purchased = _has_purchased_item($sci["type"], $sci["data_id"]);
                    if($has_purchased["purchased"]){
                    }else{
                        My_Cart::insert([
                            "user_id" => Auth::user()->id,
                            "type" => $sci["type"],
                            "data_id" => $sci["data_id"],
                            "accreditation" => $sci["accreditation"] ? json_encode($sci["accreditation"]) : null,
                            "price" => $sci["price"],
                            "discounted_price" => $sci["discounted_price"] ?? null,
                            "discount" => $sci["discount"] ?? null,
                            "voucher" => $sci["voucher"] ?? null,
                            "channel" => $sci["channel"] ?? "fast_promo",
                            "total_amount" => $sci["total_amount"] ?? 0,
    
                            "offering_type" => $sci["offering_type"] ?? null,
                            "schedule_type" => $sci["schedule_type"] ?? null,
                            "schedule_id" => $sci["schedule_id"] ?? null,
    
                            "status" => "active",
                            "created_at" => date("Y-m-d H:i:s"),
                            "updated_at" => date("Y-m-d H:i:s"),
                        ]);
                    }
                }
            }else{
                foreach($db_my_cart as $dmc){
                    $session_my_cart = array_filter($session_my_cart, function($sci) use($dmc){
                        if($sci["type"] == $dmc->type && $sci["data_id"] == $dmc->data_id){
                        }else{
                            return $sci;
                        }
                    });
                }

                if(count($session_my_cart) > 0){
                    foreach($session_my_cart as $sci){
                        $has_purchased = _has_purchased_item($sci["type"], $sci["data_id"]);
                        if($has_purchased["purchased"]){    
                        }else{
                            My_Cart::insert([
                                "user_id" => Auth::user()->id,
                                "type" => $sci["type"],
                                "data_id" => $sci["data_id"],
                                "accreditation" => $sci["accreditation"] ? json_encode($sci["accreditation"]) : null,
                                "price" => $sci["price"],
                                "discounted_price" => $sci["discounted_price"] ?? null,
                                "discount" => $sci["discount"] ?? null,
                                "voucher" => $sci["voucher"] ?? null,
                                "channel" => $sci["channel"] ?? "fast_promo",
                                "total_amount" => $sci["total_amount"] ?? 0,

                                "offering_type" => $sci["offering_type"] ?? null,
                                "schedule_type" => $sci["schedule_type"] ?? null,
                                "schedule_id" => $sci["schedule_id"] ?? null,

                                "status" => "active",
                                "created_at" => date("Y-m-d H:i:s"),
                                "updated_at" => date("Y-m-d H:i:s"),
                            ]);
                        }
                    }
                }
            }

            Session::forget("my_cart_in_session");
        }
    }
}

if(! function_exists('_get_my_cart_items')){
    function _get_my_cart_items(){
        $my_cart = [];
        if(Auth::check()){
            $db_my_cart = My_Cart::select(
                "type", "data_id", "price", "discounted_price", "discount", "voucher", "channel", "total_amount",
                "accreditation", "offering_type", "schedule_type", "schedule_id"
            )->where([
                "user_id" => Auth::user()->id,
                "status" => "active"
            ])->get()->toArray();

            if(count($db_my_cart) > 0){
                $db_my_cart = array_map(function($dmc){
                    if($dmc["type"]=="course"){
                        $data_record = Course::select(
                            "id", "title", "url", "provider_id",
                            "course_poster as poster", "published_at"
                        )->where([
                            "prc_status" => "approved",
                            "deleted_at" => null,
                            "id" => $dmc["data_id"],
                        ])->first()->toArray();
                    }else{
                        $data_record = Webinar::select(
                            "id", "title", "url", "provider_id",
                            "webinar_poster as poster", "published_at"
                        )->where([
                            "deleted_at" => null,
                            "id" => $dmc["data_id"],
                        ])->first()->toArray();
                    }

                    if($dmc["accreditation"]){
                        if($dmc["type"] == "webinar" && $dmc["offering_type"] == "without"){
                            $dmc["accreditation"] = null;
                        }else{
                            $accreditation = array_map(function($acc){
                                $proffesion = Profession::select("title")->find($acc->id);
                                $acc->title = $proffesion->title;
                                
                                return $acc;
                            }, $dmc["accreditation"] ? json_decode($dmc["accreditation"]) : []);
    
                            $dmc["accreditation"] = $accreditation;
                        }
                    }
                    
                    $dmc["poster"] = _get_image($dmc["type"], $data_record["id"])["small"];
                    $dmc["title"] = $data_record["title"];
                    $dmc["url"] = $data_record["url"];
                    $dmc["provider_id"] = $data_record["provider_id"];
                    return $dmc;
                }, $db_my_cart);

                $my_cart = $db_my_cart;
            }
        }else{
            $my_cart =  Session::has("my_cart_in_session") ? Session::get("my_cart_in_session") : [];
            $my_cart = array_map(function($mca){
                return $mca;
            }, $my_cart);
        }

        return $my_cart;
    }
}

if(! function_exists('_get_fast_discount')){
    function _get_fast_discount($type, $data_id){
        
        $discount = Voucher::select("discount", "voucher_code", "channel")->where([
            "channel" => "fast_promo",
            "status" => "active",
        ])->where("type", "!=", "rc_once_applied");

        if(Auth::check()){
            $discount = $discount->where("type", "!=", "manual_apply");
        }else{
            $discount = $discount->where("type", "=", "auto_applied");
        }

        $discount = $discount->orderBy("discount", "desc")->first();

        if($discount){
            if($type=="course"){
                $course = Course::select("provider_id")->find($data_id);
                if($course){
                    $provider = Provider::find($course->provider_id);
                    if($provider && $provider->allow_marketing==1){
                        return $discount;
                    }
                }
            }else{
                $webinar = Webinar::select("provider_id", "type")->find($data_id);
                if($webinar){
                    $provider = Provider::find($webinar->provider_id);
                    if($provider && $provider->allow_marketing==1 && $webinar->type == "official"){
                        return $discount;
                    }
                }
            }
        }

        return null;
    }
}

if(! function_exists('_get_applied_discount')){
    function _get_applied_discount($type, $data_id, $discount_credit){
        $discount_credit = explode(":", $discount_credit);
        
        $discount = Voucher::select("discount", "voucher_code", "channel")->where([
            "voucher_code" => $discount_credit[1],
            "status" => "active",
        ])->first();

        if($discount){
            $discount->discount = $discount_credit[0];
            return $discount;
        }

        return null;
    }
}

if(! function_exists('_get_revenue_percent')){
    function _get_revenue_percent($provider_id, $promo, $type){
        
        $revenue_sharing = Revenue_Sharing::where([
            "provider_id" => $provider_id,
            "type" => $type,
        ])->first();

        if($revenue_sharing){
            switch ($promo) {
                case 'fast_promo':
                    return [
                        "fast" => $revenue_sharing->fast_revenue,
                        "provider" => 100 - $revenue_sharing->fast_revenue,
                        "promoter" => 0,
                    ];

                    break;

                case 'provider_promo':
                    return [
                        "fast" => 100 - $revenue_sharing->provider_revenue,
                        "provider" => $revenue_sharing->provider_revenue,
                        "promoter" => 0,
                    ];

                    break;
                
                
                default:
                    $left = 100 - $revenue_sharing->promoter_revenue;
                    $half = $left > 0 ? $left/2 : 0;

                    return [
                        "fast" => $half,
                        "provider" => $half,
                        "promoter" => $revenue_sharing->promoter_revenue,
                    ];

                    break;
            }
        }

        if($type=="course"){
            return [
                "fast" => 50,
                "provider" => 50,
                "promoter" => 0, 
            ];
        }

        return [
            "fast" => 15,
            "provider" => 85,
            "promoter" => 0, 
        ];
    }
}

if(! function_exists('_has_purchased_item')){
    function _has_purchased_item($type, $data_id){

        if(Auth::check()){
            $item_purchase = Purchase_Item::select(
                "purchase_id", "payment_status", "fast_status"
            )->where([
                "user_id" => Auth::user()->id,
                "type" => $type,
                "data_id" => $data_id,
            ])->whereIn("payment_status", ["waiting", "pending", "paid"])->first();
            
            if($item_purchase){

                $purchase = Purchase::select("fast_status")->find($item_purchase->purchase_id);
                if($purchase && $purchase->fast_status == "waiting"){
                    return [
                        "purchased" => true,
                        "goto" => "items",
                        "url" => "/profile/settings",
                        "message" => "Unable to add to cart! Payment is currently processing..."
                    ];
                }

                if(in_array($item_purchase->payment_status, ["waiting", "pending"])){
                    return [
                        "purchased" => true,
                        "message" => "Unable to add to cart! Item is currently <b>waiting/pending for payment</b>. Please check your purchase details..."
                    ];
                }else{
                    // paid
                    if($type=="course"){
                        $course = Course::select("allow_retry", "url")->find($data_id);
                        if($course){
                            if(in_array($item_purchase->fast_status, ["complete", "passed"])){
                                return [
                                    "purchased" => true,
                                    "goto" => "items",
                                    "url" => "/profile/settings",
                                    "message" => "Unable to add to cart! Course is already completed, please check your <b>\"My Items\"</b>"
                                ];
                            }

                            if($item_purchase->fast_status=="incomplete" || ($item_purchase->fast_status=="failed" && $course->allow_retry)){
                                return [
                                    "purchased" => true,
                                    "goto" => "live",
                                    "url" => "/course/live/{$course->url}",
                                    "message" => "Unable to add to cart! Course is incomplete, please continue by visiting the live course"
                                ];
                            }
                        }
                    }else{
                        $webinar = Webinar::select("allow_retry", "url")->find($data_id);
                        if($webinar){
                            if(in_array($item_purchase->fast_status, ["complete", "passed"])){
                                return [
                                    "purchased" => true,
                                    "goto" => "items",
                                    "url" => "/profile/settings",
                                    "message" => "Unable to add to cart! Webinar is already completed, please check your <b>\"My Items\"</b>"
                                ];
                            }

                            if($item_purchase->fast_status=="incomplete" || ($item_purchase->fast_status=="failed" && $webinar->allow_retry)){
                                return [
                                    "purchased" => true,
                                    "goto" => "live",
                                    "url" => "/webinar/live/{$webinar->url}",
                                    "message" => "Unable to add to cart! Webinar is incomplete, please continue by attending the live webinar"
                                ];
                            }
                        }
                    }

                    return [
                        "purchased" => false,
                    ];
                }
            }
        }
        
        return [
            "purchased" => false,
        ];
    }
}
 
if(! function_exists('_can_view_live')){
    function _can_view_live($type, $data_id){
        
        if(Auth::check()){
            $item_purchase = Purchase_Item::select("purchase_id")->where([
                "user_id" => Auth::user()->id,
                "type" => $type,
                "data_id" => $data_id,
                "payment_status" => "paid",
            ])->first();
            
            if($item_purchase){
                $purchase = Purchase::select("fast_status")->find($item_purchase->purchase_id);
                if($purchase && $purchase->fast_status=="confirmed"){
                    return true;
                }
            }
        }
        
        return false;
    }
}

if(! function_exists('_get_my_items')){
    function _get_my_items(){
        $data = [];

        if(Auth::check()){
            $purchases = Purchase::select("id")->where([
                "user_id" => Auth::user()->id,
                "payment_status" => "paid",
                "fast_status" => "confirmed",
            ])->get();

            if($purchases->count()>0){
                foreach($purchases as $purchase){
                    $items = Purchase_Item::select(
                        "id", "type", "data_id", "credited_cpd_units", "price", "discounted_price",
                        "discount", "voucher", "channel", "total_amount", 
                        "offering_type", "schedule_type", "schedule_id", 
                        "payment_status", "fast_status"
                    )->where(["purchase_items.purchase_id" => $purchase->id])->get();

                    foreach($items as $item){

                        
                        if($item->type=="course"){
                            $data_record = Course::find($item->data_id);
                            $data_record->fast_status = $item->fast_status;
                            if(in_array($item->fast_status, ["complete", "passed"])){
                                $data_record->progress = 100;
                            }else{
                                if($item->fast_status == "failed"){
                                    $data_record->progress =  _get_live_course_progress($data_record->id);
                                }else{
                                    if($data_record->fast_cpd_status == "published"){
                                        $data_record->fast_status = "published";
                                        $data_record->session_start = $data_record->session_start;
                                    }else{
                                        $data_record->progress =  _get_live_course_progress($data_record->id);
                                    }
                                }
                            }
                        }else{
                            $data_record = Webinar::find($item->data_id);
                            $data_record->fast_status = $item->fast_status;
                            if(in_array($item->fast_status, ["complete", "passed"])){
                                $data_record->progress = 100;
                            }else{
                                if($item->fast_status == "failed"){
                                    $data_record->progress =  [];
                                }else{
                                    if($data_record->fast_cpd_status == "published"){
                                        $data_record->fast_status = "published";
                                    }else{ 
                                        if($data_record->fast_cpd_status == "live"){
                                            $data_record->fast_status = "live";
                                        }
                                        $data_record->progress =  [];
                                    }
                                }
                            }

                            $data_record->webinar_schedule = _get_webinar_available_schedule($data_record, $item);
                        }
                        
                        $data_record->poster = _get_image($item->type, $item->data_id)["small"];
                        $data_record->type = $item->type;
                        $data[] = $data_record;
                    }
                }
            }
        }

        return $data;
    }
}

if(! function_exists('_get_checkout_items')){
    function _get_checkout_items(){
        $data = [];
        $my_cart = _get_my_cart_items();

        $data["my_cart"] = $my_cart;
        $data["total_items"] = count($my_cart);

        $data["units"] = [];
        $data["total_original_price"] = 0;
        $data["total_discounted_amount"] = 0;
        $data["total_amount"] = 0;
        $data["vouchers"] = [];

        $free_counter = 0;
        foreach ($my_cart as $mc) {
            if($mc["total_amount"]<=0){
                $free_counter++;
            }else{
                $data["total_original_price"] += $mc["price"];
                $data["total_discounted_amount"] += $mc["discounted_price"] > 0 ? ($mc["price"] - $mc["discounted_price"]) : 0;
                $data["total_amount"] += $mc["total_amount"];
               
                if($mc["voucher"]){
                    if(!in_array($mc["voucher"], $data["vouchers"])){
                        $data["vouchers"][] = $mc["voucher"];
                    }
                }
            }
        }

        if($data["total_discounted_amount"]){
            $data["total_discount_percent"] = 100 - ((($data["total_original_price"] - $data["total_discounted_amount"]) / $data["total_original_price"]) * 100);
        }else{
            $data["total_discount_percent"] = 0;
        }

        if($free_counter == count($my_cart)){
            return ["data"=>$data, "free" => true];
        }
        
        return $data;
    }
}

if(! function_exists('_checkout_free_items')){
    function _checkout_free_items($data){
        $reference_number = date("Y").strtoupper(uniqid());

        $purchase = new Purchase;
        $purchase->user_id = Auth::user()->id;
        $purchase->reference_number = $reference_number;
        $purchase->total_amount = 0;
        $purchase->payment_at = date("Y-m-d H:i:s");
        $purchase->payment_status = "paid";
        $purchase->payment_notes = "Purchased free webinars";
        $purchase->fast_status = "confirmed";
        $purchase->created_at = date("Y-m-d H:i:s");
        $purchase->updated_at = date("Y-m-d H:i:s");

        if($purchase->save()){
            foreach ($data as $mc) {

                _update_referral_voucher($mc["voucher"], $mc["discount"], true);
                Purchase_Item::insert([
                    "purchase_id" => $purchase->id,
                    "user_id" => Auth::user()->id,
                    "type" => $mc["type"],
                    "data_id" => $mc["data_id"],
                    "credited_cpd_units" => $mc["accreditation"] ? json_encode($mc["accreditation"]) : null,
                    "price" => $mc["price"],
                    "discounted_price" => $mc["discounted_price"],
                    "discount" => $mc["discount"],
                    "voucher" => $mc["voucher"],
                    "channel" => $mc["channel"],
                    "total_amount" => $mc["total_amount"],
                    "offering_type" => $mc["offering_type"],
                    "schedule_type" => $mc["schedule_type"],
                    "schedule_id" => $mc["schedule_id"],
                    "fast_revenue" => 0,
                    "provider_revenue" => 0,
                    "promoter_revenue" => 0,
                    "payment_status" => "paid",
                    "fast_status" => "incomplete",
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s")
                ]);

                My_Cart::where([
                    "user_id" => Auth::user()->id,
                    "type" => $mc["type"],
                    "data_id" => $mc["data_id"],
                    "status" => "active",
                ])->update([
                    "status" => "checkout",
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            }
        }
        
        return redirect("/profile/settings");
    }
}

/**
 * function that determines if the user can purchase the webinar series
 */
if(! function_exists('_purchase_series')){
    function _purchase_series($webinar_id, $series_id){
        $today = date("Y-m-d");

        $series = Webinar_Series::select("sessions")->find($series_id);
        if($series){
            $sessions = json_decode($series->sessions);
            foreach ($sessions as $key => $session) {
                $session_data = Webinar_Session::select("id")->whereDate("session_date", "<=", $today)->where([
                    "id" => $session,
                    "webinar_id" => $webinar_id,
                    "deleted_at" => null,
                ])->first();

                if($session_data){
                    return false;

                    break;
                }
            }
        }

        return true;
    }
}
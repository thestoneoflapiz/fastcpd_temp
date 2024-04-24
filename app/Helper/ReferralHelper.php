<?php

use App\{
    User, 
    Voucher,
    Referral, Referral_Code,
    Purchase_Item,
};

use Illuminate\Support\Facades\{Auth};

if (! function_exists('_unique_referral_code')) {

    function _unique_referral_code()
    {
        $referral_code = Str::random(8);
        while(Referral_Code::select("referral_code")->where('referral_code', $referral_code)->exists()) {
            $referral_code = Str::random(8);
        }

        return strtoupper($referral_code);
    }
}

if (! function_exists('_unique_voucher_code')) {

    function _unique_voucher_code($id)
    {
        $voucher = "FASTRV{$id}".date("y");
        return $voucher;
    }
}

if (! function_exists('_get_referer')) {

    function _get_referer($code)
    {
        $referer = Referral_Code::where("referral_code", "=", $code)->where("status", "!=", "blocked")->first();
        return $referer;
    }
}

if (! function_exists('_referer_reached_limit')) {

    function _referer_reached_limit($referer_id)
    {
        $referer = Referral::select("id")->where("referer_id", "=", $referer_id)->get();
        return $referer ? ($referer->count()>=10 ? true: false) : false;
    }
}


if (! function_exists('_sign_referral')) {

    function _sign_referral($referer, $referral, $code)
    {
        if(_referer_reached_limit($referer)){
            return false;
        }

        $referral_user = User::select("email_verified_at", "status", "prc_id")->find($referral);
        if($referral_user && $referral_user->emai_verified_at!=null && $referral_user->prc_id!=null && $referral_user->status=="active"){
            Referral::insert([
                "referer_id" => $referer,
                "referral_id" => $referral,
                "status" => "approved",
                "referral_code" => $code,
                "created_at" => date("Y-m-d H:i:s") 
            ]);

            $referer = Referral_Code::where("referral_code", "=", $code)->where("status", "!=", "blocked")->first();
            if($referer){
                $referer->total_redeemed = $referer->total_redeemed + 1;
                $refere->save();
            }
            return true;
        }else{
            Referral::insert([
                "referer_id" => $referer,
                "referral_id" => $referral,
                "referral_code" => $code,
                "status" => "waiting",
                "created_at" => date("Y-m-d H:i:s") 
            ]);

            return true;
        }
    }
}

if (! function_exists('_referer_voucher')) {

    function _referer_voucher($voucher_code)
    {
        if(Auth::check()){
            $referer = Referral_Code::select("id")->where([
                "referer_id" => Auth::user()->id,
                "voucher_code" => $voucher_code
            ])->where("status", "!=", "blocked")->first();
            
            return $referer ? true : false;
        }

        return false;
    }
}

if (! function_exists('_referer_discount')) {

    function _referer_discount($voucher_code)
    {
        if(Auth::check()){
            $referer = Referral_Code::select("id", "total_redeemed")->where([
                "referer_id" => Auth::user()->id,
                "voucher_code" => $voucher_code
            ])->where("status", "!=", "blocked")->first();
            
            if($referer){
                // "discount", "voucher_code", "channel"
                if($referer->total_redeemed == 10){
                    // check if used the same voucher on 50% discount

                    $find_50 = Purchase_Item::select("id")
                    ->where([
                        "voucher" => $voucher_code,
                        "discount" => 50,
                        "channel" => "fast_promo",
                    ])->whereIn("payment_status", ["waiting", "pending", "paid"])->first();

                    if($find_50){
                        $referer->discount = 0;
                    }else{
                        $referer->voucher_code = $voucher_code;
                        $referer->discount = 50;
                        $referer->channel = "fast_promo";
                    }

                    return $referer;
                }
                
                if($referer->total_redeemed >= 5){
                    // check if used the same voucher on 30% discount
                    $find_30 = Purchase_Item::select("id")
                    ->where([
                        "voucher" => $voucher_code,
                        "discount" => 30,
                        "channel" => "fast_promo",
                    ])->whereIn("payment_status", ["waiting", "pending", "paid"])->first();

                    if($find_30){
                        $referer->discount = 0;
                    }else{
                        $referer->voucher_code = $voucher_code;
                        $referer->discount = 30;
                        $referer->channel = "fast_promo";
                    }
                    
                    return $referer;
                }
                
                if($referer->total_redeemed < 5){

                    $referer->total_redeemed = 0;
                    return $referer;
                }
            }
        }

        return null;
    }
}

if (! function_exists('_update_referral_voucher')) {

    function _update_referral_voucher($voucher_code, $discount, $add)
    {
        if(Auth::check()){
            $referer = Referral_Code::where([
                "referer_id" => Auth::user()->id,
                "voucher_code" => $voucher_code
            ])->where("status", "!=", "blocked")->first();
            
            if($referer && $referer->referer_id==Auth::user()->id){
                if($discount==50){
                    $referer_discounts = json_decode($referer->discount);
                    $referer_discounts->v50 = $add ? 1 : 0;
                    $referer->discount = json_encode($referer_discounts);
                    $referer->save();
                }

                if($discount==30){
                    $referer_discounts = json_decode($referer->discount);
                    $referer_discounts->v30 = $add ? 1: 0;
                    $referer->discount = json_encode($referer_discounts);
                    $referer->save();
                }
            }
        }
    }
}
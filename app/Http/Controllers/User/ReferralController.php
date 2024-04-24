<?php

namespace App\Http\Controllers\User;

use App\{
    User, Purchase_Item, Referral_Code, Referral, Voucher,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Mail, Auth};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;

use App\Mail\{
    NotificationMail
};

use Response; 
use Session;
use DateTime;

class ReferralController extends Controller
{
    function index(){
        $user_id = Auth::user()->id;
        $data = [];

        $referral_code = Referral_Code::where([
            "referer_id" => $user_id,
        ])->first();

        if($referral_code){
            $referral_code->discount = json_decode($referral_code->discount);
        }

        $referrals = Referral::select("referrals.id","referrals.referral_id","referrals.created_at","referrals.status","users.name","users.first_name")->where([
            "referrals.referer_id" => $user_id,
        ])->join("users", "users.id","referrals.referral_id", "left")->get();

        $joined_referral = Referral::select("referral_id")
            ->where("referral_id", $user_id)->first();

        $today = new DateTime();
        $created = new DateTime(Auth::user()->created_at);
        $interval = $today->diff($created);
        $days = $interval->format('%a');

        $data = [
            "referral" => $referral_code,
            "referrals" => $referrals,
            "joined" => $joined_referral ? true : false,
            "new_user" => (int)$days <= 7 ? true : false,
        ];

        return view("page.referral.user.index", $data);
    }

    function generate_code(Request $request) : JsonResponse
    {
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $referral_code = Referral_Code::where("referer_id", "=", $user_id)->first();
            if($referral_code){
                if($referral_code->status=="blocked"){
                    return response()->json([ "message" => "You are blocked from this program. You have violated the terms and conditions in abuse or fraud."], 402);
                }
                return response()->json([ "message" => "Referral code already exists! Please reload you browser. Thank you!"], 402);
            }

            $referral_code = _unique_referral_code($user_id);
            $voucher_code = _unique_voucher_code($user_id);

            Referral_Code::insert([
                "referer_id" => $user_id,
                "referral_code" => $referral_code,
                "voucher_code" => $voucher_code,
                "discount" => json_encode([
                    "v30" => 0, "v50" => 0,
                ]),
                "created_at" => date("Y-m-d H:i:s")
            ]);

            Voucher::insert([
                "channel" => "fast_promo",
                "discount" => 30,
                "voucher_code" => $voucher_code,
                "description" => "Referral Code Program voucher assigned for ".Auth::user()->name ?? Auth::user()->first_name,
                "session_start" => date("Y-m-d H:i:s"),
                "session_end" => date("Y-m-d H:i:s", strtotime("+1 year")),
                "type" => "rc_once_applied",
                "status" => "active",
                "created_by" => $user_id,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ]);

            return response()->json([]);
        }

        return response()->json([ "message" => "Unauthorized!"], 401);
    }

    function join_referer(Request $request) : JsonResponse
    {
        $referer = _get_referer($request->_rc);
        if($referer){
            if(_sign_referral($referer->referer_id, Auth::user()->id, $request->_rc)){
                _notification_insert("signup",Auth::user()->id , Auth::user()->id, "Referred", "Thank you for joining FastCPD through the Referral Code program!", "/");
                return response()->json([]);
            }else{
                return response()->json(["message" => "Referral Limit! Sorry, the referral code you used has reached limit!"], 422);
            }
        }

        return response()->json(["message" => "Referral code doesn't exist or blocked from our records"], 422);
    }
}

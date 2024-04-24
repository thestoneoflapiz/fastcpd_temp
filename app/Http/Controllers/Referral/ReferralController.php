<?php

namespace App\Http\Controllers\Referral;

use App\{
    User, Purchase_Item, Referral_Code, Referral
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Auth};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;

use Response; 
use Session;

class ReferralController extends Controller
{
    function index(Request $request)
    {
        if($request->code){
            $referral_code = Referral_Code::where([
                "referral_code" => $request->code,
            ])->first();
            if($referral_code){
                if($referral_code->status=="blocked"){
                    Session::flash("warning", "Referer is blocked from the Referral Code program!");
                }else{
                    if(Auth::check()){
                        if(Auth::user()->id!=$referral_code->referer_id){
                            $referral = Referral::where("referral_id", "=", Auth::user()->id)->first();
                            if($referral){
                                Session::flash("info", "Sorry! You already joined another referer, you can only join once.");
                                return redirect("/");
                            }
                        }
                    }

                    if($request->has("action") && $request->action=="rcs"){
                        Session::forget("referer_sign_code");
                        Session::put("referer_sign_code", $request->code);
                        return redirect("/referer/signup");
                    }

                    $user = User::select("id", "username", "name", "first_name", "image")->find($referral_code->referer_id);
                    $data = [
                        "referral" => $referral_code,
                        "referer" => $user,
                    ];

                    return view("page.public.referer.index", $data);
                }
            }

            Session::flash("info", "Referer not found!");
        }

        return redirect("/");
    }

    function signup_index(Request $request)
    {
        $data = [];
        
        if(Session::has("referer_sign_code")){
            $referral_code = Referral_Code::where([
                "referral_code" => Session::get("referer_sign_code"),
            ])->first();

            if($referral_code){
                if($referral_code->status=="blocked"){
                    Session::flash("warning", "Referer is blocked from the Referral Code program!");
                }else{
                    if(Auth::check()){
                        if(Auth::user()->id!=$referral_code->referer_id){
                            $referral = Referral::where("referral_id", "=", Auth::user()->id)->first();
                            if($referral){
                                Session::flash("info", "Sorry! You already joined another referer, you can only join once.");
                                return redirect("/");
                            }
                        }else{
                            Session::flash("info", "You already joined FastCPD.");
                            return redirect("/profile/referral");
                        }
                    }

                    $user = User::select("id", "username", "name", "first_name", "image")->find($referral_code->referer_id);
                    $data = [
                        "referral" => $referral_code,
                        "referer" => $user,
                    ];

                    return view("page.public.referer.signup", $data);
                }
            }
            Session::flash("info", "Referer not found!");
        }

        return redirect("/");
    }
}

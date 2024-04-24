<?php

namespace App\Http\Controllers\Auth;

use App\{
    User
};

use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Hash};
use App\Mail\{PublicVerificationEmail};

use Response;
use Session;

class ThirdPartyAuthentication extends Controller
{
    function login(Request $request) : JsonResponse
    {
        $social = $request->social;
        $socialID = null;
        $socialName = null;
        $socialFirstName = null;
        $socialLastName = null;
        $socialToken = null;
        $socialExpire = null;
        $socialEmail = null;
        $socialImage = null;

        switch ($social) {
            case 'facebook':
                $socialID =  $request->response["details"]["id"];
                $socialExpire = $request->response["authResponse"]["expiresIn"];
                $socialToken = $request->response["authResponse"]["accessToken"];
                $socialEmail = $request->response["details"]["email"];
                $socialName = $request->response["details"]["name"];
     
            break;
            
            case 'google':
                $socialID =  $request->response["id"];
                $socialEmail = $request->response["email"];
                $socialName = $request->response["name"];
                $socialFirstName = array_key_exists("first_name", $request->response) ? $request->response["first_name"] : null;
                $socialLastName = array_key_exists("last_name", $request->response) ? $request->response["last_name"] : null;
                $socialImage = array_key_exists("image", $request->response) ? $request->response["image"] : null;

            break;
        }

        $look_for_user = User::where([
            "authSocial" => $social,
            "authID" => $socialID
        ])->first();

        if($look_for_user){
            $look_for_user->authExpiresIn = $socialExpire;
            $look_for_user->authToken = $socialToken;
            $look_for_user->status = "active";
            $look_for_user->updated_at = date("Y-m-d H:i:s");
            $look_for_user->save();

            if($look_for_user->first_name && $look_for_user->last_name){
                if(Auth::check()){
                    _save_session_cart_to_db();
                    return response()->json([], 200);
                }else{
                    Auth::loginUsingId($look_for_user->id);
                    _save_session_cart_to_db();
                    return response()->json([
                        "redirect" => "/"
                    ], 200);
                }
            }
            
            if(Auth::check()){
                _save_session_cart_to_db();
                return response()->json([], 200);
            }else{
                Auth::loginUsingId($look_for_user->id);
                _save_session_cart_to_db();
                return response()->json([
                    "redirect" => "/auth/social/register"
                ], 200);
            } 
        }

        $valid_email = User::where("email", "=", $socialEmail)->first();
        if($valid_email){
            return response()->json([
                "message" => "Unable to ".ucwords($social)." login! email already registered in our system"
            ], 422);
        }

        $new_user = new User;
        $new_user->authSocial = $social;
        $new_user->authID = $socialID;
        $new_user->authExpiresIn = $socialExpire;
        $new_user->authToken = $socialToken;

        $new_user->username = "user".date("ymd-i");
        $new_user->name = $socialName;
        $new_user->first_name = $socialFirstName;
        $new_user->last_name = $socialLastName;
        $new_user->image = $socialImage; 
        $new_user->email = $socialEmail;

        $small_letter_without_spaces_first_name = strtolower(str_replace(" ","", $socialFirstName)).date("Y");
        $new_user->password = bcrypt($small_letter_without_spaces_first_name);
        $new_user->professions = json_encode([
            [
                "id"=> 1, // default
                "prc_no"=> null
            ]
        ]);
        $new_user->prc_id = null;
        $new_user->status = "active";
        $new_user->created_at = date("Y-m-d H:i:s");
        $new_user->updated_at = date("Y-m-d H:i:s");
        $new_user->save();

        if(Session::has("referer_sign_code")){
            $_rc_signed = Session::get("referer_sign_code");
            Session::forget("referer_sign_code");

            $referer = _get_referer($_rc_signed);
            if($referer){
                if(_sign_referral($referer->referer_id, $new_user->id, $_rc_signed)){
                    _notification_insert("signup",$new_user->id , $new_user->id, "Referred", "Thank you for joining FastCPD through the Referral Code program!", "/");
                }else{
                    _notification_insert("signup",$new_user->id , $new_user->id, "Referral Limit", "Sorry, the referral code you used has reached limit!", "/");
                }
            }
            _notification_insert("signup",$new_user->id , $new_user->id, "Referred", "Thank you for joining FastCPD through the Referral Code program!", "/");
        }

        Auth::loginUsingId($new_user->id);

        _save_session_cart_to_db();
        _notification_insert("signup",$new_user->id , $new_user->id, "Signup", "Welcome to FastCPD! You can now start learning while earning CPD units. Don't forget to verify your email", "/");
        _send_notification_email($new_user->email,"signup",null,$new_user->id);
        Mail::to($socialEmail)->send(new PublicVerificationEmail($new_user));

        return response()->json([
            "redirect" => "/auth/social/register"
        ], 200);
    }

    function register(Request $request){
        if(Auth::check()){
            if(Auth::user()->authSocial && Auth::user()->last_name==null){
                return view('auth/social-register');
            }
        }

        Session::flash("info", "You can edit your account profile on your settings");
        return redirect("/profile/settings");
    }

    function register_action(Request $request) : JsonResponse
    {
        if(Auth::check()){
            $user = User::find(Auth::user()->id);
            $user->updated_at = date("Y-m-d H:i:s");
            $user->first_name = $request->register_first_name;
            $user->middle_name = $request->register_middle_name;
            $user->last_name = $request->register_last_name;
            $user->name = "{$request->register_first_name} {$request->register_middle_name} {$request->register_last_name}";
            $user->password = bcrypt($request->register_password);
            $user->professions = json_encode([
                [
                    "id" => $request->register_profession,
                    "prc_no" => null
                ]
            ]);
            $user->save();
            return response()->json([]);
        }

        Auth::logout();
        return response()->json([
            "message" => "Session expired! Please login again!"
        ],422);
    }
}

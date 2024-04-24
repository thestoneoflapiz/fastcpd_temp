<?php

namespace App\Http\Controllers\Auth;

use App\{
    User, Password
};

use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Hash};
use App\Mail\{PublicVerificationEmail, PublicResetEmail};

use Response;
use Session; 

class PublicUserController extends Controller
{
    function check_user_profile(Request $request) : JsonResponse {
        if(Auth::check()){
            if(Auth::user()->authSocial){
                if(Auth::user()->last_name == null){
                    return response()->json([
                        "redirect" => "/auth/social/register"
                    ]);
                }
            }
            return response()->json([]);
        }

        return response()->json([]);
    }
    
    function signup(Request $request) : JsonResponse {
        
        $valid_email = User::where("email", "=", $request->upemail)->first();
        if($valid_email){
            return response()->json(["status"=>400,"msg"=>'Email already registered in our system']);
        }else{
            $user = new User;

            $user->first_name = $request->first_name;
            $user->middle_name = $request->middle_name;
            $user->last_name = $request->last_name;
            $user->name = "{$request->first_name} {$request->middle_name} {$request->last_name}";

            $user->email = $request->upemail;
            $user->professions = json_encode([
                [
                    "id"=> $request->profession,
                    "prc_no"=> null
                ]
            ]);
            $user->prc_id = null;
            $user->resume ='';
            $user->password = bcrypt($request->uppassword);
            $user->status = "active";

            if($user->save()){
                $user->username = "user00{$user->id}".date("ymd-i");
                $user->save();
                
                if($request->has("_rc_signed")){
                    Session::forget("referer_sign_code");
                    $referer = _get_referer($request->_rc_signed);
                    if($referer){
                        if(_sign_referral($referer->referer_id, $user->id, $request->_rc_signed)){
                            _notification_insert("signup",$user->id , $user->id, "Referred", "Thank you for joining FastCPD through the Referral Code program!", "/");
                        }else{
                            _notification_insert("signup",$user->id , $user->id, "Referral Limit", "Sorry, the referral code you used has reached limit!", "/");
                        }
                    }
                }

                _notification_insert("signup",$user->id , $user->id, "Signup", "Welcome to FastCPD! You can now start learning while earning CPD units. Don't forget to verify your email", "/");
                _send_notification_email($user->email,"signup",null,$user->id);
                // Comment if no email-verification
                $this->publicNotice($user, $request->upemail);
                
                Auth::logout();
                Auth::loginUsingId($user->id);
                /**
                 * save session my cart
                 * 
                 */
                _save_session_cart_to_db();

                return response()->json(["status"=>200]);

            }else{
                return response()->json(["status"=>400, "msg"=> 'Unable to save user! Please try again later.']);
            }
        }

        return response()->json(["status"=>404, "msg"=> "Something went wrong. Please try again later!"]);
    }

    function publicNotice($user, $email){
        Mail::to($email)->send(new PublicVerificationEmail($user));
    }

    function verify(Request $request){
        
        $verified = User::find($request->id);

        if($verified && $verified->email_verified_at==null){
            $verified->email_verified_at = date('Y-m-d H:i:s');
            $verified->status = "active";
            $verified->save();
            
            //_logs("users", "User {{$verified->first_name}} {{$verified->last_name}} has been verified!");
            $request->session()->flash('success', 'Your account has been verified!');
            return redirect()->route('home');
        }        

        $request->session()->flash('warning', 'Your verification has already expired. Sorry!');
        return redirect()->route('home');
    }

    function reset(Request $request){

        $verified = User::where("email", "=", $request->email)->first();
        if($verified){
            if($verified->email_verified_at == null){
                return response()->json(["status"=>400,"msg"=>"Sorry. Your account is not verified."]);
            }else if($verified->status == "inactive"){
                return response()->json(["status"=>400,"msg"=>"Sorry. Your account is not activated."]);
            }else{

                $update = Password::where(["email"=>$verified->email, "status"=>"active"])->get();
                foreach ($update as $pass) {
                    $pass->status = "inactive";
                    $pass->save();
                }
                
                $password = new Password;
                $password->email = $verified->email;
                $password->status = "active";
                $password->created_at = date("Y-m-d H:i:s");
                $password->save();

                Mail::to($verified->email)->send(new PublicResetEmail($verified));

                return response()->json(["status"=>200]);
            }
        }

        return response()->json(["status"=>400,"msg"=>"Sorry. Your email is not registered in our system."]);
    }
    
    function password_reset(Request $request){
        
        $reset = User::find($request->user);

        if($reset){
            $pass_reset = Password::where("email", "=", $reset->email)->orderBy('created_at', 'desc')->first();
            if($pass_reset && $pass_reset->status =="active"){
                $reset->password = bcrypt($request->password);
            
                if($reset->save()){
                    $pass_reset->status = "inactive";
                    $pass_reset->save();
    
                    $request->session()->flash('link', config('app.link'));
                    return response()->json(['status'=>200, 'redirect'=>'/success?type=reset']);
                }

                return response()->json(['status'=>400, "msg"=> "Sorry. Something went wrong. Please try again."]);
            }

            return response()->json(['status'=>400, "msg"=> "Sorry. Your password reset has expired."]);
        }        

        return response()->json(['status'=>400, "msg"=> "Sorry. We're unable to find your account. Please try again later."]);
    }

}

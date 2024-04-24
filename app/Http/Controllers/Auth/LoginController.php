<?php

namespace App\Http\Controllers\Auth;

use App\{User, Co_Provider, Provider};

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

use Session;

class LoginController extends Controller
{

    function signin(Request $request) : JsonResponse {

        $user = User::where("email", "=", $request->email)->where("status", "!=", "delete")->first();

        if(!$user){
            return response()->json(['status'=>'401', 'msg'=> "Sorry. Your account does not exist in our records."]);
        }

        if($user->status=="inactive" && $user->email_verified_at!=null){
            return response()->json(['status'=>'401', 'msg'=> "Sorry. Your account is not activated."]);
        }

        if($user->status=="delete"){
            return response()->json(['status'=>'401', 'msg'=> "Sorry. Your account has been deleted."]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['status'=>'401', 'msg'=> "Password do not match! Please try again."]);
        }

        // if($user->email_verified_at==null && $user->superadmin == 'none'){
        //     return response()->json(['status'=>'401', 'msg'=> "Sorry. Your account is not yet verified."]);
        // }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember )) {
            if($user->superadmin == "waiting"){
                $user->superadmin = "active";
                $user->status = "active";
                $user->save();
            }

            /**
             * save existing session cart
             * 
             */
            _save_session_cart_to_db();
            return response()->json(['status'=>'200']);
        }

        return response()->json(['status'=>'401', 'Incorrect email and password combination! Please try again.']);
    }

    function signout(){
        Auth::logout();
        Session::forget("my_cart_in_session");

        return redirect('/');
    }
}

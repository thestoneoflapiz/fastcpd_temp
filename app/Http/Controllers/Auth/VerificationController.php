<?php

namespace App\Http\Controllers\Auth;

use App\{User};

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{

    function verify(Request $request) {
        if($request->id=="missing"){
            $request->session()->flash('warning', 'Your verification has already expired. Sorry!');
        }
    
        $user = USER::find($request->id);

        if($user && $user->email_verified_at == null){
            
            $request->session()->put('user', $user->id);

            $request->session()->flash('success', 'Your account has been verified!');
            return redirect()->route('home');
        }else{
            $request->session()->flash('warning', 'Your verification has already expired. Sorry!');
            return redirect()->route('home');
        }

    }

    function password_verify(Request $request) : JsonResponse{
        
        $verified = User::find($request->user);

        if($verified){
            $verified->password = bcrypt($request->password);
            $verified->email_verified_at = date('Y-m-d H:i:s');
            $verified->status = "active";

            //_logs("users", "User {{$verified->first_name}} {{$verified->last_name}} has been verified!");

            $verified->save();

                Auth::logout();
                Auth::loginUsingId($verified->id);

            $request->session()->flash('success', 'Your email '.$verified->email.' is already verified!');
            
            return response()->json(['status'=>200, 'redirect'=> "/"]);
        }        
        return response()->json(['status'=>400, "msg"=> "Sorry. Your verification is already expired."]);
    }

}

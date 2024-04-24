<?php

namespace App\Http\Controllers\Auth\Promoter;

use App\{User, Promoter};

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

use Session;

class AuthPromoterController extends Controller
{

    function verify(Request $request) {

        // $hash_code = $request->id;
        if($request->id=="missing"){
            $request->session()->flash('warning', 'Your verification has already expired. Sorry!');
        }
    
        $user = Promoter::find($request->id);
        if($user && $user->status == "invited"){
            
            $request->session()->put('user', $user->id);
            return view('/auth/promoter/password',$user);

            // $request->session()->flash('success', 'Your account has been verified!');
            // return redirect()->route('home');
        }else{
            $request->session()->flash('warning', 'Your verification has already expired. Sorry!');
            return redirect()->route('home');
        }

    }

    function password(Request $request) : JsonResponse{
        
        $verified = Promoter::find($request->id);
        $voucher = _get_promoter_voucher($verified->id);
        
        if($verified){
            $verified->password = bcrypt($request->password);
            $verified->status = "active";

            $verified->save();
            Voucher::where('id', $voucher->id)
            ->update([
                'status' => "active"
            ]);

            $request->session()->flash('success', 'Your email '.$verified->email.' is already verified!');
            
            return response()->json(['status'=>200, 'redirect'=> "/"]);
        }        
        return response()->json(['status'=>400, "msg"=> "Sorry. Your verification is already expired."]);
    }

    function login(Request $request) : JsonResponse{
        
        $user = Promoter::where("email", "=", $request->email)->where("status", "!=", "delete")->first();

        if(!$user){
            return response()->json(['status'=>'401', 'msg'=> "Sorry. Your account does not exist in our records."]);
        }

        if($user->status=="invited"){
            return response()->json(['status'=>'401', 'msg'=> "Sorry. Your account is not verified."]);
        }

        if($user->status=="delete" || $user->status=="inactive" ){
            return response()->json(['status'=>'401', 'msg'=> "Sorry. Your account has been deleted."]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['status'=>'401', 'msg'=> "Password do not match! Please try again."]);
        }

        // if($user->email_verified_at==null && $user->superadmin == 'none'){
        //     return response()->json(['status'=>'401', 'msg'=> "Sorry. Your account is not yet verified."]);
        // }
        if (Auth::guard('promoters')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember )) {
            
            return response()->json(['status'=>'200']);
        }

        return response()->json(['status'=>'401', 'Incorrect email and password combination! Please try again.']);
    }

    function signout(){
        Auth::guard('promoters')->logout();
        Session::forget("my_cart_in_session");

        return redirect('/');
    }

}

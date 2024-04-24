<?php

namespace App\Http\Controllers\Superadmin;

use App\{
    User
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB, Hash};

use Response; 
use Session;

class AccountController extends Controller
{
    function index()
    {
        return view("page.superadmin.account.index");
    }

    function save(Request $request) : JsonResponse
    {
        $user = User::find(Auth::user()->id);
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();

        return response()->json([]);
    }

    function change_password(Request $request) : JsonResponse
    {
        $old = $request->old_password;
        $new = bcrypt($request->new_password);
        
        if (Hash::check($old, Auth::user()->password)) {
            $user = User::find(Auth::user()->id);
            $user->password = $new;

            if($user->save()){
                return response()->json([]);
            }else{
                return response()->json(["message" => "Unable to save password."], 400);
            }
        }

        return response()->json(["message" => "Password didn't match!"], 400);  
    }
}
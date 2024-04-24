<?php

namespace App\Http\Controllers\Payout_setting;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Course, Instructor_Permissions, Image_Intervention,
    Webinar,Purchase_Item
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class PayoutSettingController extends Controller
{

    function index(Request $request)
    {   
        // $request->session()->forget('payout_query');
        // Session::forget('payout_query');
        $data = array(
            "account_info"=>_get_payment_settings(Auth::guard('promoters')->user()->id)
        );

        
        return view('page/promoter/payout_settings/index',$data);

    }
}
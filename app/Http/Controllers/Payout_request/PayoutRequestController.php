<?php

namespace App\Http\Controllers\Payout_request;

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

class PayoutRequestController extends Controller
{

    function index(Request $request)
    {   
        // $request->session()->forget('payout_query');
        Session::forget('payout_query');

        $data = array(
            "voucher" => _get_promoter_voucher(Auth::guard('promoters')->user()->id),
            "current_balance" => _get_balance(Auth::guard('promoters')->user()->id),
            "commission_list" => _get_commission_logs(Auth::guard('promoters')->user()->id),
        );
    return view('page/promoter/payout_request/index', $data);

    }
}
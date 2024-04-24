<?php

namespace App\Http\Controllers\Api;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Course, Instructor_Permissions, Image_Intervention,
    Webinar,Purchase_Item,Payout,Promoter
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Carbon\Carbon;
use Response; 
use Session;

class PayoutSettingController extends Controller
{
    function update(Request $request) : JsonResponse
    {
        $data = array(
            "bpi_acc_name" => $request->bpi_acc_name,
            "bpi_acc_number" => $request->bpi_acc_number,
            "bpi_contact" => $request->bpi_contact,
            "bdo_acc_name" => $request->bdo_acc_name,
            "bdo_acc_number" => $request->bdo_acc_number,
            "bdo_contact" => $request->bdo_contact,
            "gcash_acc_name" => $request->gcash_acc_name,
            "gcash_contact" => $request->gcash_contact
        );

        $promoter = Promoter::where('id', Auth::guard('promoters')->user()->id)
        ->update(['payout_settings' => json_encode($data)]);
            

        return response()->json([]);
    }
}

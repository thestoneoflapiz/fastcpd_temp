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

class MarketingToolController extends Controller
{

    function update(Request $request) : JsonResponse
    {
        $promoter = Promoter::where('id', Auth::guard('promoters')->user()->id)
        ->update(['marketing_references' => $request->link]);

        return response()->json([]);
    }
}
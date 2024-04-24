<?php

namespace App\Http\Controllers\Marketing_tool;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Course, Instructor_Permissions, Image_Intervention,
    Webinar,Purchase_Item,Promoter
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class MarketingToolController extends Controller
{

    function index(Request $request)
    {   
        $link = Promoter::where('id',Auth::guard('promoters')->user()->id)->first();
        $data = array(
            "marketing_link" => $link->marketing_references
        );
        return view('page/promoter/marketing_tool/index',$data);
    }
}
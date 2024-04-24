<?php

namespace App\Http\Controllers\Api;

use App\{
    User, Provider, Logs,
    Instructor, Course, Voucher,
    Webinar
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;

use DateTime;
use Response;
use Session;

class VoucherController extends Controller
{
    function voucher_courses(Request $request) : JsonResponse{
        $voucher_id = $request->voucher_id;
        $voucher = Voucher::find($voucher_id);

        $courses = [];
        if($voucher->data_id){
            if(json_decode($voucher->data_id)->courses !==null){
                foreach(json_decode($voucher->data_id)->courses as $cID){
                
                    $course = Course::select("id", "title", "price")->find($cID);
                    if($course && $course->price){
    
                        $percent = $voucher->discount / 100;
                        $discounted = $percent * $course->price;
                        $courses[] = array(
                            "title" => $course->title,
                            "price" => number_format($course->price, 2),
                            "discount_percentage" => $voucher->discount,
                            "discount_price" => number_format(( $course->price - $discounted ), 2),
                        );
                    }
                }
            }
            if(json_decode($voucher->data_id)->webinars !==null){
                foreach(json_decode($voucher->data_id)->webinars as $cID){
                    $webinar = Webinar::select("id", "title", "prices")->find($cID);
                    if($webinar && $webinar->prices){
                        $with=json_decode($webinar->prices)->with;
                        $without=json_decode($webinar->prices)->without;
                        $percent = $voucher->discount / 100;
                        $discounted = $percent *$with;
                        $courses[] = array(
                            "title" => $webinar->title,
                            "price" => "with:₱".number_format($with, 2) . "<br/>without:₱".number_format($without, 2),
                            "discount_percentage" => $voucher->discount,
                            "discount_price" => "with:₱".number_format(($with - $discounted ), 2). "<br/>without:₱".number_format($without, 2),
                        );
                    }
                }
            }
        }

        return response()->json(["data"=>$courses]);
    }

    function search_voucher(Request $request) : JsonResponse
    {

        return response()->json([]);
    }
}

<?php

namespace App\Http\Controllers\User;

use App\{
    User, Provider, Co_Provider as COP, Course, Instructor,
    Purchase, Purchase_Item, My_Cart, Instructor_Resume,Certificate,

    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Instructor_Permission, Webinar_Progress,
    Webinar_Performance, Webinar_Rating, Webinar_Attendance,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

use App\Mail\{VerificationEmail, PublicVerificationEmail};
use Response; 

class ProfileController extends Controller
{
    function __construct()
    {
        $this->s3Client = new S3Client([
            'profile' => 'default',
            'region' => 'ap-northeast-1',
            'version' => '2006-03-01'
        ]);
    }

    function overview(Request $request){ 
        return view('page/profile/settings');
    }

    function fetch_overview_list(Request $request) : JsonResponse
    {
        $purchase_records = Purchase::select(
            "id", "reference_number", "vouchers", "total_discount", "processing_fee", "total_amount", 
            "payment_gateway", "payment_method", "payment_status", "payment_notes", "payment_at",
            "fast_status", "created_at"
        )->where("user_id", "=", Auth::user()->id)
        ->whereMonth("created_at", "=", $request->month)
        ->whereYear("created_at", "=", $request->year)
        ->orderBy("created_at", "desc")->get();
        
        if($purchase_records->count()>0){
            $purchase_records = array_map(function($purchase){

                $purchase_items = Purchase_Item::select(
                    "id", "type", "data_id", "credited_cpd_units", "price", "discounted_price",
                    "discount", "voucher", "channel", "total_amount", 
                    "offering_type", "schedule_type", "schedule_id", 
                    "payment_status", "fast_status"
                )->where("purchase_id", "=", $purchase["id"])->get()->toArray();

                $purchase_items = array_map(function($item){
                    if($item["type"] == "course"){
                        $data_record = Course::select("id", "title", "course_poster as poster", "course_poster_id", "url")->find($item["data_id"]);
                        $progress = _get_live_course_progress($data_record->id);

                        $item["progress_list"] = [
                            "complete" => $progress==100 ? true : false,
                            "feedback" => _get_live_course_feedback($data_record->id, Auth::user()->id, true),
                        ];
                    }else{
                        $data_record = Webinar::select("id", "title", "webinar_poster as poster", "webinar_poster_id", "url", "event")->find($item["data_id"]);
                        $item["progress_list"] = [
                            "complete" => _get_live_webinar_attendance($item, Auth::user()->id),
                            "feedback" => _get_live_webinar_feedback($data_record->id, Auth::user()->id, true),
                        ];
                    }

                    $item["data_record"] = $data_record;
                    if($item["discount"]){
                        $item["amount_saved"] = $item["price"] - $item["discounted_price"];
                    }else{
                        $item["amount_saved"] = 0;
                    }

                    $item["certificate_code"] = Certificate::where(["user_id" => Auth::user()->id, "type"=>$item["type"],"data_id"=>$item["data_id"]])->first();
                    return $item;
                }, $purchase_items);

                $purchase["items"] = $purchase_items;

                if(in_array($purchase["payment_method"], ["bdo", "bpib", "cbc", "lbpa", "mayb", "mbtc", "psb", "rcbc", "rsb", "ubpb", "ucpb", "aub", "bdrx", "cbcx", "ewxb", "lbxb", "mbxb", "pnb", "rcxb", "rsbb", "ubxb", "ucxb", "sbcb"])){
                    $purchase["payment_normal_method"] = "Bank Deposit/OTC";
                }elseif(in_array($purchase["payment_method"], ["bayd", "cebl", "mlh", "smr"])){
                    $purchase["payment_normal_method"] = "Payment Center";
                }else if($purchase["payment_method"]=="card"){
                    $purchase["payment_normal_method"] = "Credit/Debit Card";
                }else{
                    $purchase["payment_normal_method"] = strtoupper($purchase["payment_method"] ?? "FREE");
                }

                $purchase["created_at_string"] = date("M. d, Y", strtotime($purchase["created_at"]));
                $purchase["payment_at_string"] = $purchase["payment_at"] ? date("M. d, Y", strtotime($purchase["payment_at"])) : null;

                return $purchase;
            }, $purchase_records->toArray());
        }

        return response()->json([
            "data" => $purchase_records
        ]);
    }

    function password(Request $request) : JsonResponse
    {
        $old = $request->old;
        $new = bcrypt($request->new);
        
        if (Hash::check($old, Auth::user()->password)) {
            $user = User::find(Auth::user()->id);
            $user->password = $new;

            if($user->save()){
                return response()->json(["status" => 200]);
            }else{
                return response()->json(["status" => 422, "message" => "Unable to save password."]);
            }
        }

        return response()->json(["status" => 422, "message" => "Password didn't match!"]);
    }

    function personal(Request $request) : JsonResponse
    {
        $image = $request->image;
        $contact = $request->contact;
        $email = $request->email;
        
        $username = $request->username;
        $headline = $request->headline;
        $about = $request->about;
        $website = $request->website;
        $facebook = $request->facebook;
        $linkedin = $request->linkedin;
        $professions = $request->professions;
        $image_files = $request->image_files;
        $user = User::find(Auth::user()->id);
        
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->name = "{$request->first_name} {$request->middle_name} {$request->last_name}";
        
        if($username && Auth::user()->username != $username){
            $check = User::where("username", "=", $username)->first();
            if($check){
                return response()->json(["message" => "Username is already taken!"], 422);
            }else{
                $recheck = Provider::where("url", "=", $username)->first();
                if($recheck){
                    return response()->json(["message" => "Username is already taken!"], 422);
                }
            }

            if($user->username != $username){
                $user->username = $username;
                $user->un_change = 1;
            }
        }

        if($email && Auth::user()->email != $email){
            $check = User::where("email", "=", $email)->where("deleted_at", "=", null)->first();
            if($check){
                return response()->json(["message" => "Email is already taken!"], 422);
            }

            Mail::to($email)->send(new PublicVerificationEmail($user));
            $user->email_verified_at = null;
            $user->email = $email;
        }

        if($contact && Auth::user()->contact != $contact){
            $check = User::where("contact", "=", $contact)->first();
            if($check){
                return response()->json(["message" => "Contact No. is already taken!"], 422);
            }
            $user->contact = $contact;
        }
        
        $new_image = null;
        if($image){
            $exploaded = explode(",", $image);
            $image_file = end($exploaded);
            $converted_raw = base64_decode($image_file);
            $ext = explode(";", explode("/", $image)[1])[0];
            $image_name = "{$user->username}-".date("Ymds").".{$ext}";

            $s3 = \Storage::disk('s3');
            $s3Bucket = $s3->getDriver()->getAdapter()->getBucket();
            $filePath = "/images/users/{$image_name}";
            $s3->put($filePath, $converted_raw, 'public');

            $user->image = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
            $new_image = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
        }

        $user->headline = $headline;
        $user->about = $about;

        if($website){
            $user->website = $website;
        }

        if($facebook){
            $user->facebook = $facebook;
        }

        if($linkedin){
            $user->linkedin = $linkedin;
        }

        $convert = [];
        if($professions && count($professions) > 0){
            foreach ($professions as $pro) {
                if($pro["prc_no"] != null && $pro["prc_no"] ){
                    $convert[] = [
                        "id"=>$pro["id"],
                        "prc_no"=>$pro["prc_no"],
                        "expiration_date"=>$pro["expiration_date"],
                    ];
                }
            }
        
            $user->professions = json_encode($convert);
            $new_professions = json_encode($convert);
        }

        $images_to_upload = [];
        if($image_files != "same"){
            $recount_images = 0;
            foreach ($image_files as $image) {
                $recount_images++;

                if(count($convert) < $recount_images){
                    break;
                }

                if($image["type"] == "old"){
                    $images_to_upload[] = $image["file"];
                    continue;
                }

                $newFile = str_replace('"', '', $image["file"]);
                $filePath = "/resume/prc_identifications/{$newFile}";
                $real_image_file = file_get_contents(storage_path("temporary/image/".$newFile));

                $s3 = \Storage::disk('s3');
                $s3Bucket = $s3->getDriver()->getAdapter()->getBucket();
                if($s3->put($filePath, $real_image_file, 'public')){
                    $images_to_upload[] = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
                    unlink(storage_path("temporary/image/".$newFile));
                }
            }

            if(count($images_to_upload) > 0){
                $user->prc_id = json_encode($images_to_upload);
            }
        }

        // if(Auth::user()->instructor != "none"){

        //     _profile_request([
        //         "data_id" => Auth::user()->id,
        //         "link" => Auth::user()->username,
        //         "name" => Auth::user()->name,
        //         "type" => "instructor",
        //         "headline" => $headline,
        //         "about" => $about,
        //         "website" => $website,
        //         "facebook" => $facebook,
        //         "linkedin" => $linkedin,
        //         "image" => $new_image ?? Auth::user()->image,
        //         "professions" => $new_professions,
        //         "prc_certificate" => count($images_to_upload) > 0 ? json_encode($images_to_upload) : Auth::user()->prc_id,
        //         "created_by" => Auth::user()->id
        //     ]);
        //     $user = User::where("id",Auth::user()->id)
        //             ->update([
        //                 "image" => $new_image ?? Auth::user()->image,
        //                 "headline" => $headline,
        //                 "about" => $about,
        //                 "website" => $website,
        //                 "facebook" => $facebook,
        //                 "linkedin" => $linkedin,
        //                 "professions" => $new_professions,
        //                 "username" => Auth::user()->username,
        //                 "prc_id" => count($images_to_upload) > 0 ? json_encode($images_to_upload) : Auth::user()->prc_id,
        //                 "instructor" => "active",
        //             ]);
            
        //     return response()->json(["status" => 200]);
        // }else{
            if($user->save()){
                $find_user = Instructor_Resume::where([
                    "user_id" => Auth::user()->id,
                    "provider_id" => null,
                    "deleted_at" => null,
                ])->first();
        
                if($find_user){
                    $find_user->image = $new_image ?? Auth::user()->image;
                    $find_user->pic_identifications = count($images_to_upload) > 0 ? json_encode($images_to_upload) : Auth::user()->prc_id; 
                    $find_user->professions = $new_professions; 
                    $find_user->save();
                }

                return response()->json(["status" => 200]);
            }else{
                return response()->json(["status" => 422, "message" => "Unable to update personal information!"]);
            }
        // }
    }

    function signature_upload(Request $request) : JsonResponse
    {
        $user = User::find(Auth::user()->id);
        
        $signature_name = "sig-{$user->username}".uniqid().".png";
        $filePath = "/images/users/signatures";

        $s3 = Storage::disk('s3');
        $s3Bucket = $s3->getDriver()->getAdapter()->getBucket();
        $s3->putFileAs($filePath, $request->file, $signature_name, 'public');

        $signature = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}/{$signature_name}";
        $user->signature = $signature;
        if($user->save()){
            return response()->json([], 200);
        }

        return response()->json([], 422);
    }

    function pdf(Request $request) 
    {
        $pdf = $request->file;
        $newFileName = Auth::user()->username."-resume-".date("Ymd").".pdf";
        if($pdf->move(storage_path('temporary/pdf'), $newFileName)){

            return response()->json($newFileName, 200);
        }else{
            return response()->json(["error"=>"Unable to save file!"], 422);
        }
    }

    function images(Request $request) 
    {
        $image = $request->file;
        $number = $request->no_images;

        $newFileName = Auth::user()->username."-image({$number})-".date("Ymds").".{$image->getClientOriginalExtension()}";
        if($image->move(storage_path('temporary/image'), $newFileName)){
            return response()->json($newFileName, 200);
        }else{
            return response()->json(["error"=>"Unable to save file!"], 422);
        }

        $image->move(storage_path('temporary/image'), $newFileName);
        return response()->json($newFileName, 200);
    }

    function remove_course(Request $request) : JsonResponse 
    {
        $id = $request->id;
        $password = $request->password;

        if (!Hash::check($password, Auth::user()->password)) {
            return response()->json(['msg'=> "Password not match! Please try again."], 422);
        }

        $find = Course::find($id);

        $instructors = json_decode($find->instructor_id);
        $instructors = array_filter($instructors, function($user){
            if(Auth::user()->id != $user){
                return $user;
            }
        });

        if(count($instructors) > 0){
            $find->instructor_id = json_encode($instructors);
        }else{
            $find->instructor_id = null;
        }

        if($find->save()){
            return response()->json([], 200);
        }

        return response()->json(["msg"=>"Something went wrong! Please try again later."], 422);
    }

    function close_account(Request $request) : JsonResponse
    {
        $user = Auth::user();
        $password = $request->password;

        if (!Hash::check($password, Auth::user()->password)) {
            return response()->json(['msg'=> "Password not match! Please try again."], 422);
        }

        /**
         * Find if Provider Owner and has Elected Admin
         * 
         */
        if($user->provider_id){
            $elected_admin = COP::select("id", "role")->where([
                "provider_id" => $user->provider_id,
                "role" => "elected",
                "status" => "active"
            ])->where("user_id", "!=", $user->id)->first();

            if(!$elected_admin){
                return response()->json(["msg"=>"Sorry! You can't close your account until you've elected an Admin on your owned Provider."], 422);
            }else{
                $elected_admin->role = "admin";
                $elected_admin->save();
            }
        }

        /**
         * Remove all CO-Admin account including owned Provider
         * 
         */
        $evicted = COP::select("id", "status", "deleted_at", "provider_id")
        ->where("user_id", "=", $user->id)->where("status", "!=", "delete")
        ->update([
            "status" => "delete",
            "deleted_at" => date("Y-m-d H:i:s")
        ]);

        /**
         * Instructor remove associated 
         * 
         */
        $assoc_instructor = Instructor::select("id", "status", "deleted_at")
        ->where("user_id", "=", $user->id)->where("status", "!=", "delete")
        ->update([
            "status" => "delete",
            "deleted_at" => date("Y-m-d H:i:s")
        ]);


        $closing = User::find($user->id);
        $closing->status = "delete";
        $closing->instructor = "none";
        $closing->deleted_at = date("Y-m-d H:i:s");

        if($closing->save()){
            Auth::logout();
        }

        return response()->json([], 200);
    }

    function webinar_view_schedule(Request $request) : JsonResponse
    {
        $purchase_item = Purchase_Item::select(
            "type", "data_id", "schedule_type", "schedule_id"
        )->find($request->item_id);

        if($purchase_item && $purchase_item->type=="webinar"){
            if($purchase_item->schedule_type=="day"){
                $sessions = Webinar_Session::where("id", "=", $purchase_item->schedule_id)->get();
            }else{
                $series = Webinar_Series::select("sessions")->find($purchase_item->schedule_id);
                $sessions = Webinar_Session::whereIn("id", json_decode($series->sessions))
                ->orderBy("session_date")->get();
            }

            $sessions = array_map(function($session){
                $session["sessions"] = json_decode($session["sessions"]);
                $session["session_date_string"] = date("M. d, Y", strtotime($session["session_date"]));

                return $session;
            }, $sessions->toArray());

            return response()->json([
                "data" => $sessions,
            ]);
        }

        return response()->json([
            "message" => "Unable to find item!"
        ], 422);
    }
}

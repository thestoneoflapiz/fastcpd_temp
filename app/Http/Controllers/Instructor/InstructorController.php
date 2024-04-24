<?php

namespace App\Http\Controllers\Instructor;

use App\{User, Instructor, Provider, Co_Provider, Instructor_Resume};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session; 

class InstructorController extends Controller
{
    function register(Request $request) : JsonResponse 
    {
        $id = $request->user;
        
        $user = User::find($id);

        if( $user->headline && $user->about && $user->prc_id && $user->professions ){
            
            /** temporary */
            // $user->instructor = "in-review";
            $user->instructor = "active";

            if($user->save()){
                return response()->json(["status" => 200]);
            }
        } else{
            $user->instructor = "pending";

            if($user->save()){
                return response()->json(["status" => "pending"]);
            }
        }
    }

    function complete_register(Request $request) : JsonResponse
    {
        $image = $request->image;
        $name = $request->name;
        $nickname = $request->nickname;

        $username = $request->username;
        $headline = $request->headline;
        $about = $request->about;
        $professions = $request->professions;
        $image_files = $request->image_files;

        $user = User::find(Auth::user()->id);
        if($name && Auth::user()->name != $name){
            $user->name = $name;
        }

        $new_image = null;
        if($image && $image != Auth::user()->image){
            $exploaded = explode(",", $image);
            $image_file = end($exploaded);
            $converted_raw = base64_decode($image_file);
            $ext = explode(";", explode("/", $image)[1])[0];
            $image_name = "{$user->username}-".date("Ymds").".{$ext}";

            $s3 = \Storage::disk('s3');
            $s3Bucket = $s3->getDriver()->getAdapter()->getBucket();
            $filePath = "/images/user/".Auth::user()->id."/{$image_name}";
            $s3->put($filePath, $converted_raw, 'public');

            $user->image = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
            $new_image = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
        }

        $user->headline = $headline;
        $user->about = $about;
        $user->username = $username;
        // $user->instructor = "in-review";
        $user->instructor = "active";

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

                $newFile = str_replace('"', '', $image);
                $filePath = "/user/".Auth::user()->id."/prc_identifications/{$newFile}";
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

        if($user->save()){
            _profile_request([
                "data_id" => Auth::user()->id,
                "link" => Auth::user()->username,
                "name" => $name,
                "type" => "instructor",
                "headline" => $headline,
                "about" => $about,
                "image" => $new_image ?? Auth::user()->image,
                "professions" => $new_professions,
                "prc_certificate" => count($images_to_upload) > 0 ? json_encode($images_to_upload) : Auth::user()->prc_id,
                "created_by" => Auth::user()->id
            ]);

            $resume = new Instructor_Resume;
            $resume->user_id = Auth::user()->id;
            $resume->image = $new_image ?? Auth::user()->image;
            $resume->name = $name;
            $resume->nickname = $nickname;
            $resume->pic_identifications = count($images_to_upload) > 0 ? json_encode($images_to_upload) : Auth::user()->prc_id;
            $resume->professions = $new_professions;
            $resume->save();
            
            return response()->json(["status" => 200]);
        }else{
            return response()->json(["status" => 422, "message" => "Unable to update personal information!"]);
        }
    }

    function resume_save(Request $request) : JsonResponse
    {
        $nickname = $request->nickname;
        $residence_address = $request->residence_address;
        $business_address = $request->business_address;
        $mobile_number = $request->mobile_number;
        $landline_number = $request->landline_number;
        $nationality = $request->nationality;

        $major_competency_areas = $request->major_compentency_area ?? null;
        $conducted_programs = $request->conducted_programs ?? null;
        $attended_programs = $request->attended_programs ?? null;
        $major_awards = $request->major_awards ?? null;
        $college_background = $request->college_background ?? null;
        $post_graduate_background = $request->post_graduate_background ?? null;
        $work_experience = $request->work_experience ?? null;
        $aipo_membership = $request->aipo_membership ?? null;
        $other_affiliations = $request->other_affiliations ?? null;

        $find_user = Instructor_Resume::where([
            "user_id" => Auth::user()->id,
            "provider_id" => null,
            "deleted_at" => null,
        ])->first();

        if($find_user){
            $find_user->image = Auth::user()->image;
            $find_user->name = Auth::user()->name;
            $find_user->email = Auth::user()->email; 
            $find_user->nickname = $nickname; 
            $find_user->pic_identifications = Auth::user()->prc_id; 
            $find_user->professions = Auth::user()->professions; 
            $find_user->residence_address = $residence_address;
            $find_user->business_address = $business_address;
            $find_user->mobile_number = $mobile_number;
            $find_user->landline_number = $landline_number;
            $find_user->nationality = $nationality;
            
            $find_user->major_competency_areas = $major_competency_areas ? json_encode($major_competency_areas) : null;
            $find_user->conducted_programs = $conducted_programs ? json_encode($conducted_programs) : null;
            $find_user->attended_programs = $attended_programs ? json_encode($attended_programs) : null;
            $find_user->major_awards = $major_awards ? json_encode($major_awards) : null;
            $find_user->college_background = $college_background ? json_encode($college_background) : null;
            $find_user->post_graduate_background = $post_graduate_background ? json_encode($post_graduate_background) : null;
            $find_user->work_experience = $work_experience ? json_encode($work_experience) : null;
            $find_user->aipo_membership = $aipo_membership ? json_encode($aipo_membership) : null;
            $find_user->other_affiliations = $other_affiliations ? json_encode($other_affiliations) : null;
            $find_user->save();
        }else{
            $insert_resume = new Instructor_Resume;
            $insert_resume->user_id = Auth::user()->id;
            $insert_resume->image = Auth::user()->image;
            $insert_resume->email = Auth::user()->email; 
            $insert_resume->name = Auth::user()->name; 
            $insert_resume->nickname = $nickname; 
            $insert_resume->pic_identifications = Auth::user()->prc_id; 
            $insert_resume->professions = Auth::user()->professions; 
            $insert_resume->residence_address = $residence_address;
            $insert_resume->business_address = $business_address;
            $insert_resume->mobile_number = $mobile_number;
            $insert_resume->landline_number = $landline_number;
            $insert_resume->nationality = $nationality;
            
            $insert_resume->major_competency_areas = $major_competency_areas ? json_encode($major_competency_areas) : null;
            $insert_resume->conducted_programs = $conducted_programs ? json_encode($conducted_programs) : null;
            $insert_resume->attended_programs = $attended_programs ? json_encode($attended_programs) : null;
            $insert_resume->major_awards = $major_awards ? json_encode($major_awards) : null;
            $insert_resume->college_background = $college_background ? json_encode($college_background) : null;
            $insert_resume->post_graduate_background = $post_graduate_background ? json_encode($post_graduate_background) : null;
            $insert_resume->work_experience = $work_experience ? json_encode($work_experience) : null;
            $insert_resume->aipo_membership = $aipo_membership ? json_encode($aipo_membership) : null;
            $insert_resume->other_affiliations = $other_affiliations ? json_encode($other_affiliations) : null;
            $insert_resume->save();
        }

        $user = User::find(Auth::user()->id);
        $user->contact = $mobile_number;
        $user->save();

        return response()->json([]);
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
    }

    function instructors(Request $request) : JsonResponse 
    {
        $provider_id = $request->provider;
        $page = $request->page;
        $take = $request->card;
        $offset = $page * $take;

        $instructors = Instructor::with("profile")->where([
            "provider_id" => $provider_id,
        ])->whereIn("status", ["active", "inactive"])
        ->take($take)->offset($offset)->get();

        
        return response()->json(["data"=>$instructors], 200);
    }

    function resume_form(){
        $resume = Instructor_Resume::where([
            "user_id" => Auth::user()->id,
            "provider_id" => null,
            "deleted_at" => null,
        ])->first();

        $professions = json_decode(Auth::user()->professions);
        $ids = array_map(function($pro){
            return $pro->id;
        }, $professions);

        $profession_user = array_map(function($pro){
            $name = _get_profession($pro->id);
            $pro->name = $name['profession'];
            return $pro;
        }, $professions);

        if(Auth::check() && Auth::user()->instructor=="none" && Auth::user()->email_verified_at!=null){
            return redirect("/instructor/register");
        }

        return view('page/profile/resume', [
            "profession_ids" => $ids, 
            "professions_user" => $profession_user,
            "resume" => $resume,
        ]);
    }

    function resume_update(Request $request) : JsonResponse
    {
        $image = $request->image;
        $name = $request->name;
        $professions = $request->professions;
        $image_files = $request->image_files;
        $new_image = null;
        if($image != "same"){
            $exploaded = explode(",", $image);
            $image_file = end($exploaded);
            $converted_raw = base64_decode($image_file);
            $ext = explode(";", explode("/", $image)[1])[0];
            $image_name = Auth::user()->username."-".date("Ymds").".{$ext}";

            $s3 = \Storage::disk('s3');
            $s3Bucket = $s3->getDriver()->getAdapter()->getBucket();

            $filePath = "/images/user/".Auth::user()->id."/{$image_name}";
            $s3->put($filePath, $converted_raw, 'public');

            $new_image = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
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

                $newFile = str_replace('"', '', $image);
                $filePath = "/user/".Auth::user()->id."/prc_identifications/{$newFile}";
                $real_image_file = file_get_contents(storage_path("temporary/image/".$newFile));

                $s3 = \Storage::disk('s3');
                $s3Bucket = $s3->getDriver()->getAdapter()->getBucket();

                if($s3->put($filePath, $real_image_file, 'public')){
                    $images_to_upload[] = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
                    unlink(storage_path("temporary/image/".$newFile));
                }
            }
        }

        $nickname = $request->nickname;
        $residence_address = $request->residence_address;
        $business_address = $request->business_address;
        $mobile_number = $request->mobile_number;
        $landline_number = $request->landline_number;
        $nationality = $request->nationality;

        $major_competency_areas = $request->major_compentency_area ?? null;
        $conducted_programs = $request->conducted_programs ?? null;
        $attended_programs = $request->attended_programs ?? null;
        $major_awards = $request->major_awards ?? null;
        $college_background = $request->college_background ?? null;
        $post_graduate_background = $request->post_graduate_background ?? null;
        $work_experience = $request->work_experience ?? null;
        $aipo_membership = $request->aipo_membership ?? null;
        $other_affiliations = $request->other_affiliations ?? null;

        $find_user = Instructor_Resume::where([
            "user_id" => Auth::user()->id,
            "provider_id" => null,
            "deleted_at" => null,
        ])->first();

        User::where([
            "id" => Auth::user()->id
        ])->update([
            'professions' => json_encode($convert),
            'name' => $name,
            'image' => $new_image ?? $find_user->image,
            // 'username' => $request->username ?? $find_user->username,
            'contact' => $mobile_number,
            'prc_id' => $images_to_upload ? json_encode($images_to_upload) : $find_user->pic_identifications,
        ]);

        if($find_user){
            $find_user->image = $new_image ?? $find_user->image;
            $find_user->name = $name; 
            $find_user->nickname = $nickname; 
            $find_user->email = Auth::user()->email;
            $find_user->pic_identifications = $images_to_upload ? json_encode($images_to_upload) : $find_user->pic_identifications; 
            $find_user->professions = json_encode($convert); 
            $find_user->residence_address = $residence_address;
            $find_user->business_address = $business_address;
            $find_user->mobile_number = $mobile_number;
            $find_user->landline_number = $landline_number;
            $find_user->nationality = $nationality;
            
            $find_user->major_competency_areas = $major_competency_areas ? json_encode($major_competency_areas) : null;
            $find_user->conducted_programs = $conducted_programs ? json_encode($conducted_programs) : null;
            $find_user->attended_programs = $attended_programs ? json_encode($attended_programs) : null;
            $find_user->major_awards = $major_awards ? json_encode($major_awards) : null;
            $find_user->college_background = $college_background ? json_encode($college_background) : null;
            $find_user->post_graduate_background = $post_graduate_background ? json_encode($post_graduate_background) : null;
            $find_user->work_experience = $work_experience ? json_encode($work_experience) : null;
            $find_user->aipo_membership = $aipo_membership ? json_encode($aipo_membership) : null;
            $find_user->other_affiliations = $other_affiliations ? json_encode($other_affiliations) : null;
            $find_user->save();
        }else{
            $insert_resume = new Instructor_Resume;
            $insert_resume->user_id = Auth::user()->id;
            $insert_resume->image = $new_image ?? Auth::user()->image;
            $insert_resume->email = Auth::user()->email;
            $insert_resume->name = $name; 
            $insert_resume->nickname = $nickname; 
            $insert_resume->pic_identifications = $images_to_upload ? json_encode($images_to_upload) : Auth::user()->prc_id; 
            $insert_resume->professions = json_encode($convert); 
            $insert_resume->residence_address = $residence_address;
            $insert_resume->business_address = $business_address;
            $insert_resume->mobile_number = $mobile_number;
            $insert_resume->landline_number = $landline_number;
            $insert_resume->nationality = $nationality;
            
            $insert_resume->major_competency_areas = $major_competency_areas ? json_encode($major_competency_areas) : null;
            $insert_resume->conducted_programs = $conducted_programs ? json_encode($conducted_programs) : null;
            $insert_resume->attended_programs = $attended_programs ? json_encode($attended_programs) : null;
            $insert_resume->major_awards = $major_awards ? json_encode($major_awards) : null;
            $insert_resume->college_background = $college_background ? json_encode($college_background) : null;
            $insert_resume->post_graduate_background = $post_graduate_background ? json_encode($post_graduate_background) : null;
            $insert_resume->work_experience = $work_experience ? json_encode($work_experience) : null;
            $insert_resume->aipo_membership = $aipo_membership ? json_encode($aipo_membership) : null;
            $insert_resume->other_affiliations = $other_affiliations ? json_encode($other_affiliations) : null;
            $insert_resume->save();
        }

        return response()->json([]);
    }
}

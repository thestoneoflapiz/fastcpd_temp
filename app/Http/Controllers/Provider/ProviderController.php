<?php

namespace App\Http\Controllers\Provider;

use App\{
    User, Provider, Logs, Co_Provider, Provider_Permission,
    Revenue_Sharing,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;

use Response;
use Session;

class ProviderController extends Controller
{
    function session(Request $request)
    {
        $id = $request->id;
        session(["session_provider_id"=>Provider::select("id")->find($id)]);
        $request->session()->forget('evaluation');
        if(Session::get("session_provider_id")){

            if(_my_provider_permission("courses","view") && _current_provider()->status=="approved"){
                return redirect()->route("provider.courses");

            }if(_my_provider_permission("webinars","view")){
                return redirect()->route("provider.webinars");

            }elseif(_my_provider_permission("overview","view")){
                return redirect()->route("provider.overview");

            }elseif(_my_provider_permission("revenue","view")){
                return redirect()->route("provider.revenue");
                
            }elseif(_my_provider_permission("review","view")){
                return redirect()->route("provider.review");
                
            }elseif(_my_provider_permission("traffic_conversion","view")){
                return redirect()->route("provider.traffic");
                
            }elseif(_my_provider_permission("prc_completion","view")){
                return redirect()->route("provider.prc_completion");
                
            }elseif(_my_provider_permission("promotions","view")){
                return redirect()->route("provider.promotions");
                
            }elseif(_my_provider_permission("provider_profile","view")){
                return redirect()->route("provider.profile");
                
            }elseif(_my_provider_permission("instructors","view")){
                return redirect()->route("provider.instructors");
                
            }elseif(_my_provider_permission("users","view")){
                return redirect()->route("provider.users");
            }

            return redirect()->route("error", ["type"=>"404"]);
        }

        $request->session()->flash('warning', 'Provider not found!');
        return redirect()->route("home");
    }

    function register(Request $request) : JsonResponse 
    {
        $logo = $request->provider_logo;
        $seo_url = strtolower($request->seo_url);
        $name = $request->name;
        $accreditation = $request->accreditation;
        $accreditation_expiration_date = date("Y-m-d H:i:s", strtotime($request->accreditation_expiration_date));

        $email = $request->email;
        $contact = $request->contact;
        $profession = $request->profession;

        $this->validate($request, [
            'seo_url' => 'required|unique:providers,url|unique:users,username',
            'profession' => 'required',
            'name' => 'required|unique:providers,name',
            'email' => 'required|unique:providers,email',
            'contact' => 'required|unique:providers,contact',
        ]);

        $provider = new Provider;
        $provider->url = $seo_url;
        $provider->name = $name;

        $provider->accreditation_number = $accreditation;
        $provider->accreditation_expiration_date = $accreditation_expiration_date;

        $provider->email = $email;
        $provider->contact = $contact;
        $provider->profession_id = json_encode($profession);
        $provider->created_by = Auth::user()->id;
        $provider->created_at = date("Y-m-d H:i:s");
        $provider->status = "in-review";

        if($logo){
            $exploaded = explode(",", $logo);
            $logo_file = end($exploaded);
            $converted_raw = base64_decode($logo_file);
            $ext = explode(";", explode("/", $logo)[1])[0];
            $logo_name = "{$seo_url}-".date("Ymd").".{$ext}";

            $s3 = Storage::disk('s3');
            $s3Bucket = $s3->getDriver()->getAdapter()->getBucket();

            $filePath = "/images/providers/{$logo_name}";
            $s3->put($filePath, $converted_raw, 'public');
            
            $provider->logo = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
        }

        if($accreditation){
            $this->validate($request, [
                'accreditation' => 'unique:providers,accreditation_number',
            ]);
        }
        
        if($provider->save()){
            Revenue_Sharing::insert([
                [
                    "provider_id" => $provider->id,
                    "type" => "course",
                    "fast_revenue" => "50",
                    "provider_revenue" => "75",
                    "promoter_revenue" => "30",
                    "created_at" => date("Y-m-d H:i:s"),
                ],
                [
                    "provider_id" => $provider->id,
                    "type" => "webinar",
                    "fast_revenue" => "15",
                    "provider_revenue" => "90",
                    "promoter_revenue" => "30",
                    "created_at" => date("Y-m-d H:i:s"),
                ]
            ]);

            _profile_request([
                "data_id" => $provider->id,
                "link" => $seo_url,
                "name" => $name,
                "type" => "provider",
                "image" => $provider->logo,
                "professions" => json_encode($profession),
                "accreditation_number" => $accreditation,
                "accreditation_expiration_date" => $accreditation_expiration_date, 
                "created_by" => Auth::user()->id
            ]);

            $user = User::find(Auth::user()->id);
            if($user){
                $user->provider_id = $provider->id;
                $user->save();

                $co_provider = new Co_Provider;
                $co_provider->user_id = Auth::user()->id;
                $co_provider->provider_id = $provider->id;
                $co_provider->status = "active";
                $co_provider->created_by = Auth::user()->id;
                $co_provider->created_at = date("Y-m-d H:i:s");
                $co_provider->save();      
                
                $modules = _generate_permissions([], true);
                /**
                 * Merge data permission and user info
                 * 
                 */
                $set_permission = array_map( function($perm) use($provider){
                    return array_merge($perm, [
                        "user_id" => Auth::user()->id, 
                        "provider_id" => $provider->id,
                        "created_by" => 0,
                        "created_at" => date("Y-m-d H:i:s"),
                    ]);
                }, $modules);
                Provider_Permission::insert($set_permission);
            }
            _send_notification_email(Auth::user()->email,"become_provider",$provider->id,Auth::user()->id);
            return response()->json(["status" => 200, "id" => $provider->id]);
        }else{
            return response()->json(["status" => 422, "message" => "Unable to save provider information!"]);
        }
    }

    function profile(Request $request) : JsonResponse 
    {
        $logo = $request->logo;
        $name = $request->name;
        $accreditation = $request->accreditation;
        $accreditation_expiration_date = date("Y-m-d H:i:s", strtotime($request->accreditation_expiration_date));
        $profession = $request->profession;
        $headline = $request->headline;
        $about = $request->about;
        $website = $request->website;
        $facebook = $request->facebook;
        $linkedin = $request->linkedin;
        
        $provider = Provider::find($request->provider_id);

        $logo_to_upload = null;
        if($logo && $logo != "same"){
            $exploaded = explode(",", $logo);
            $logo_file = end($exploaded);
            $converted_raw = base64_decode($logo_file);
            $ext = explode(";", explode("/", $logo)[1])[0];
            $logo_name = "{$provider->url}-".date("Ymds").".{$ext}";

            $s3 = \Storage::disk('s3');
            $s3Bucket = $s3->getDriver()->getAdapter()->getBucket();
            $filePath = "/images/providers/{$logo_name}";
            $s3->put($filePath, $converted_raw, 'public');

            $logo_to_upload = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
        }
    
        $data = [
            "data_id" => $provider->id,
            "name" => $provider->name,
            "link" => $provider->url,
            "type" => "provider",
            "headline" => $headline,
            "about" => $about,
            "website" => $website,
            "facebook" => $facebook,
            "linkedin" => $linkedin,
            "image" => $logo_to_upload ?? $provider->logo,
            "professions" => json_encode($profession),
            "accreditation_number" => $accreditation,
            "accreditation_expiration_date" => $accreditation_expiration_date, 
            "created_by" => Auth::user()->id
        ];

        $provider = Provider::find($provider->id);

        $provider->name = $provider->name;
        $provider->url = $provider->url;
        $provider->logo = $logo_to_upload ?? $provider->logo;
        $provider->headline = $headline;
        $provider->about = $about;
        $provider->website = $website;
        $provider->facebook = $facebook;
        $provider->linkedin = $linkedin;
        $provider->profession_id = json_encode($profession);
        $provider->accreditation_number = $accreditation;
        $provider->accreditation_expiration_date = $accreditation_expiration_date;
        $provider->status = "approved";
        $provider->save();

        if(_profile_request($data)){
            return response()->json(["status" => 200]);
        }else{
            return response()->json(["status" => 422, "message" => "Unable submit request of change!"]);
        }
    }

    function pdf(Request $request) 
    {
        $pdf = $request->file;
        $newFileName = uniqid()."-PRC-".date("Ymd").".pdf";
        if($pdf->move(storage_path('temporary/pdf'), $newFileName)){
            return response()->json($newFileName, 200);
        }else{
            return response()->json(["error"=>"Unable to save file!"], 422);
        }
    }

    function suggestions(Request $request) : JsonResponse
    {

        $title = $request->title;
        $remove_unwanted = preg_replace("/[^a-zA-Z-0-9]/", "-", $title);
        $remove_duplicate = preg_replace('/([-])\\1+/', '$1', $remove_unwanted);
        $trim_dash = trim($remove_duplicate, "-");
        $trim_dash = strtolower($trim_dash);
        
        $random_addons = ["top", "live", "new", "provider", "learn", "best", "good"];
        $suggestions = [ $trim_dash, date('Y-').$trim_dash, $trim_dash.date('-Y')];
        $suggestions = array_map(function($value){
            $find = Provider::where("url", "=", $value)->first();
            if($find){
                $new_value = $random_addons[rand(0, 6)]."-{$value}";
                $find_new = Provider::where("url", "=", $value)->first();

                if($find_new){
                    return "{$new_value}-".uniqid();
                }

                return $new_value;
            }

            return $value;
        }, $suggestions);
        
        
        return response()->json(["data" => $suggestions], 200);
    }

}

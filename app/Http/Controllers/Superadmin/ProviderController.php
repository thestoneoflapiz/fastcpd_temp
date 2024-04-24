<?php

namespace App\Http\Controllers\Superadmin;

use App\{User, Provider, Co_Provider, Instructor, Course, Profile_Requests as PR, Provider_Permission};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class ProviderController extends Controller
{
    function verification_list(Request $request) : JsonResponse
    {
        
        $profile_requests = PR::select("profile_requests.id","profile_requests.created_at","users.name as submitted_by", 
                                "providers.name as provider","providers.url"  )
                                ->where(["profile_requests.status"=>"in-review"])
                                ->where(["profile_requests.type"=>"provider"])
                                ->leftJoin("providers","providers.id","profile_requests.data_id")
                                ->leftJoin("users","users.id","profile_requests.created_by")
                                ->get();
                               
        if(count($profile_requests)){
            foreach($profile_requests as $request){
                $requests[]= [
                    "id" => $request->id,
                    "submitted_at" => date("M j, Y",strtotime($request->created_at)),                 
                    "submitted_by"=> $request->submitted_by,
                    "provider" => $request->provider,
                    "url" => $request->url,
    
                ];
            }
        }else{
            $requests= [];
        }                      
        
    
        return response()->json(["data"=>$requests, "total"=>count($requests)], 200);
    
    }

    function list(Request $request) : JsonResponse
    {
        $request = $request->all();
        $query = $request['query'];
        $pagination = $request['pagination'];
        // $offset = $pagination['page'] == 1 ? 0 : ($pagination['page'] - 1) * $pagination['perpage'];
        // $sort = $request['sort'];
        $page = $request["pagination"]["page"];
        $perPage =  $request["pagination"]["perpage"] ? ($request["pagination"]["perpage"] <= 0 ? 10 : $request["pagination"]["perpage"]) : '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $providers = Provider::select("providers.id", "providers.name", "providers.email", "providers.contact", "providers.created_at", "providers.url","pr.status")
                ->where("providers.status", "!=", null)
                ->leftJoin("profile_requests as pr",function($join){
                    $join->on("pr.type","=",DB::raw("'provider'"));
                    $join->on("pr.data_id","providers.id");
                })->orderBy("providers.created_at","desc");

        $quotient = floor($providers->count() / $perPage);
        $reminder = ($providers->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $request["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $providers->count(),
            "sort"=> $request["sort"]["sort"] ?? "asc",
            "field"=> $request["sort"]["field"] ?? "status",
        );

        if(array_key_exists("sort", $request)){
            $retouch_sort = in_array($request["sort"]["field"], ["status","name", "role"]) ? $request["sort"]["field"] : "status";
            $providers = $providers->orderBy($retouch_sort, $request["sort"]["sort"])
                ->skip($offset)->take($request["pagination"]["perpage"]);
        }else{
            $providers = $providers->skip($offset)->take(10);  
        }

        $providers = $providers->get();
       
        $providers = array_map(function($provider){

            $coproviders = Co_Provider::with("profile")->where([
                "provider_id"=>$provider['id'],
                "deleted_at"=>null,
            ])->get();
            $instructors = Instructor::with("profile")->where([
                "provider_id"=>$provider['id'],
                "deleted_at"=>null,
            ])->get();

            return[
                "provider_id" => $provider['id'],
                "id" => $provider['id'],
                "name" => $provider['name'],
                "email" => $provider['email'],
                "contact" => $provider['contact'],
                "url" => $provider['url'],
                "status" => $provider['status'],
                
                "coproviders" => $coproviders,
                "instructors" => $instructors,
                "created_at" => date("M. d, Y", strtotime($provider['created_at'])),
            ];
        }, $providers->toArray());
      
    
        return response()->json(["data"=>$providers,"meta" => $meta, "total"=>count($providers)], 200);
    }

    function reject(Request $request) : JsonResponse
    {
        $data = PR::find($request->id);
        if($data){
            $data->status = "blocked";
            $data->notes = $request->notes;
            $data->save();
        }

        return response()->json([]);

    }

    function approve(Request $request) : JsonResponse
    {
        $prequest = PR::find($request->id);
        if($prequest){
            $prequest->status = "approved";
            $prequest->save();

            $provider = Provider::find($prequest->data_id);
            $provider->name = $prequest->name;
            $provider->url = $prequest->link;
            $provider->logo = $prequest->image;
            $provider->headline = $prequest->headline;
            $provider->about = $prequest->about;
            $provider->website = $prequest->website;
            $provider->facebook = $prequest->facebook;
            $provider->linkedin = $prequest->linkedin;
            $provider->profession_id = $prequest->professions;
            $provider->accreditation_number = $prequest->accreditation_number;
            $provider->accreditation_expiration_date = $prequest->accreditation_expiration_date;
            $provider->status = "approved";
            $provider->save();
        }
        
        return response()->json([]);
    }
    function list_details(Request $request):JsonResponse{
        $paramTable = $request->all();
        $provider = $paramTable["query"];

        $page = $paramTable["pagination"]["page"];
        $perPage =  $paramTable["pagination"]["perpage"] ? ($paramTable["pagination"]["perpage"] <= 0 ? 10 : $paramTable["pagination"]["perpage"]) : '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $coproviders = Co_Provider::select(
            "users.name","co_providers.role","co_providers.provider_id",
            "users.id"
        )
        ->where([
            "co_providers.provider_id"=>$provider['providerID'],
            "co_providers.deleted_at"=>null,
            "co_providers.status" => "active"
        ])
        ->leftJoin("users","users.id","co_providers.user_id");
        
        $quotient = floor($coproviders->count() / $perPage);
        $reminder = ($coproviders->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $coproviders->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "name",
        );
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["name", "role"]) ? $paramTable["sort"]["field"] : "name";
            $coproviders = $coproviders->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $coproviders = $coproviders->skip($offset)->take(10);  
        }

        $coproviders = $coproviders->get();

        $data = array_map(function($co_prov){
            $instructors = Instructor::with("profile")->where([
                "provider_id"=>$co_prov['provider_id'],
                "deleted_at"=>null,
            ])->get();
            
            $permissions = Provider_Permission::where("provider_id",$co_prov['provider_id'])
                                ->where("user_id",$co_prov['id'])->get();

            return [
                "id" => $co_prov['id'],
                "name" => $co_prov['name'],
                "role" => $co_prov['role'],
                "permissions" => $permissions,
                "instructors" => $instructors,
            ];
        },$coproviders->toArray());

        
        return  response()->json(["data"=>$data,"meta"=>$meta],200);
    }
}

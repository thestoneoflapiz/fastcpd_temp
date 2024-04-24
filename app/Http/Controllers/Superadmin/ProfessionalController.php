<?php

namespace App\Http\Controllers\Superadmin;

use App\{
    User, Provider, Co_Provider, Instructor, Course, 
    Profession, Top_Profession,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class ProfessionalController extends Controller
{
    function list(Request $request) : JsonResponse
    {
        $request = $request->all();
        $query = $request['query'];
        $pagination = $request['pagination'];
        $offset = $pagination['page'] == 1 ? 0 : ($pagination['page'] - 1) * $pagination['perpage'];
        $sort = $request['sort'] ?? false;

        $page = $request["pagination"]["page"];
        $perPage =  $request["pagination"]["perpage"] ? ($request["pagination"]["perpage"] <= 0 ? 10 : $request["pagination"]["perpage"]) : '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;


        $users = User::where(["superadmin"=>"none"])->orderBy("created_at","asc");

        $quotient = floor($users->count() / $perPage);
        $reminder = ($users->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        $meta = array(
            "page"=> $request["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $users->count(),
            "sort"=> $request["sort"]["sort"] ?? "asc",
            "field"=> $request["sort"]["field"] ?? "status",
        );

        // if($query['generalSearch']){
        //     $users = $users->whereRaw("`name` LIKE '%{$query['generalSearch']}%' OR `email` LIKE '%{$query['generalSearch']}%' OR `status` LIKE '%{$query['generalSearch']}%'
        //         OR DATE_FORMAT('%M. %d, %y', `created_at`) LIKE '%{$query['generalSearch']}%' OR `contact` LIKE '%{$query['generalSearch']}%'
        //     ");
        // }
        if(array_key_exists("sort", $request)){
            $retouch_sort = in_array($request["sort"]["field"], ["status","name", "user_roles","created_at","email","contact"]) ? $request["sort"]["field"] : "status";
            $users = $users->orderBy($retouch_sort, $request["sort"]["sort"])
                ->skip($offset)->take($request["pagination"]["perpage"]);
        }else{
            $users = $users->skip($offset)->take(10);  
        }
        $users = $users->get();
       
        $users = array_map(function($user){
            $user['user_roles'] = [];

            $user['created_at'] = date("F j, Y",strtotime($user['created_at']));

            if($user['provider_id'] != null){
                $user['user_roles'][] = "Provider";
            }

            if(Co_Provider::where("user_id", "=", $user['id'])->first()){
                $user['user_roles'][] = "Co-Provider";
            }

            if($user['instructor'] != "none"){
                $user['user_roles'][] = "Instructor";
            }

            if($user['accreditor'] != "none"){
                $user['user_roles'][] = "Accreditor";
            }else{
                $user['user_roles'][] = "Professional";
            }
            if($user['professions'] != null){
                if(is_array( json_decode($user['professions'])) ){
                    $rearranged = array_map(function($pro) {
                        $profession = Profession::select("title")->find($pro->id);
        
                        $pro->title = $profession->title;
                        return $pro;
                    }, json_decode($user['professions']));
                    $user['professions'] = json_encode($rearranged);
                }else{
                 
                }
               
               
            }
           

            return $user;
        }, $users->toArray());
        return response()->json(["data"=>$users,"meta" => $meta, "total"=>count($users)], 200);
    }

    function professions(Request $request) : JsonResponse 
    {
        $professions = SuperadminPermission::where("user_id", "=",  $request->id)->get();
        
        $redefined = [];
        
        foreach ($professions as $key => $perm) {
            if($perm->view && $perm->create){
                $redefined[] = $perm->module_name;
            }
        }

        return response()->json(["data"=>$redefined], 200);
    }

    function top_page(Request $request){
        $data = [];
        
        $tops = Top_Profession::all();
        $data["top_professions"] = $tops;
        
        return view('page/superadmin/profession/top', $data);
    }

    function top_pr_save(Request $request) : JsonResponse{

        $profession = [];
        foreach($request->professions as $key => $pr){
            $array_ = explode("/", $pr);

            $profession[] = [
                "profession_id" => $array_[0],
                "profession" => $array_[1]
            ];
        }

        Top_Profession::truncate();
        Top_Profession::insert($profession);

        return response()->json([]);

    }
}

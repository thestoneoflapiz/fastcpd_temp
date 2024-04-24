<?php

namespace App\Http\Controllers\Superadmin;

use App\{User, Provider, Co_Provider, Instructor, Course, SuperadminPermission};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use App\Mail\{SuperadminInvitation};

use Response; 
use Session;


class UserController extends Controller
{
    function list(Request $request) : JsonResponse
    {
        $request = $request->all();
        $query = $request['query'];
        $pagination = $request['pagination'];
        $offset = $pagination['page'] == 1 ? 0 : ($pagination['page'] - 1) * $pagination['perpage'];
        $sort = $request['sort'] ?? null;

        $superadmin = User::where("superadmin", "!=", "none")->where(["status"=>"active"]);

        if($query['generalSearch']){
            $superadmin = $superadmin->whereRaw("`name` LIKE '%{$query['generalSearch']}%' OR `email` LIKE '%{$query['generalSearch']}%' OR `superadmin` LIKE '%{$query['generalSearch']}%'");
        }

        if ($sort) {
            $superadmin = $superadmin->take($pagination['perpage'])->offset($offset)->orderBy($sort["field"], $sort["sort"])->get();
        }else{
            $superadmin = $superadmin->take($pagination['perpage'])->offset($offset)->orderBy("name", "asc")->get();
        }

        return response()->json(["data"=>$superadmin, "total"=>$superadmin->count()], 200);
    }

    function add(Request $request) : JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|unique:users',
        ]);  
        
        $password = $this->random_string();
        $permissions = [];
        

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->superadmin = 'waiting';
        $user->created_by = Auth::user()->id;
        $user->created_at = date('Y-m-d H:i:s');
        if($user->save()){
            Mail::to($user->email)->send(new SuperadminInvitation($user, $password));
        
            foreach ($request->permissions as $permission) {
                $permissions[] = [
                    'user_id' => $user->id,
                    "module_name" => $permission,
                    "view" => 1,
                    "create" => 1,
                    "edit" => 1,
                    "delete" => 1,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            SuperadminPermission::insert($permissions);
        
            return response()->json([], 200);
        }

        return response()->json([], 422);
    }

    function random_string() {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function permissions(Request $request) : JsonResponse 
    {
        $permissions = SuperadminPermission::where("user_id", "=",  $request->id)->get();
        
        $redefined = [];
        
        foreach ($permissions as $key => $perm) {
            if($perm->view && $perm->create){
                $redefined[] = $perm->module_name;
            }
        }

        return response()->json(["data"=>$redefined], 200);
    }

    function permission_action(Request $request) : JsonResponse
    {
        $permissions = [];
        foreach ($request->permissions as $permission) {
            $permissions[] = [
                'user_id' => $request->id,
                "module_name" => $permission,
                "view" => 1,
                "create" => 1,
                "edit" => 1,
                "delete" => 1,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
        SuperadminPermission::where("user_id", "=", $request->id)->delete();
        SuperadminPermission::insert($permissions);

        return response()->json([], 200);
    }
    
}

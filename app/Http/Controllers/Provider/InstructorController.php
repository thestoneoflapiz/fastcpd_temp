<?php

namespace App\Http\Controllers\Provider;

use App\{User, Provider, Logs, Invitation, Provider_Permission, Instructor};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;
use App\Mail\{ProviderInstructorInvitation};

use Response;
use Session;

class InstructorController extends Controller
{
    function instructors(Request $request) : JsonResponse
    {
        return response()->json($this->paginatedQueryInstructors($request));
    }

    protected function paginatedQueryInstructors(Request $request)
    {   
        $inst = Instructor::select("user_id as id", "status")->where("provider_id", "=", $request->provider)
            ->where("status", "!=", "delete")->get();
        
        $total = $inst->count();
        $totalIds = array_map(function($user){
            return $user['id'];
        }, $inst->toArray());

        $request->session()->get("inst_query");
        $paramFilter = $request->session()->get("inst_query");
        $paramTable = $request->all();

        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $users = User::select("a.id", "a.image", "a.name", "a.email", "a.contact", "b.status")
            ->from("users as a")->leftJoin("instructors as b", "a.id", "b.user_id")
            ->where('b.status', '!=', 'delete')->where("b.provider_id", "=", $request->provider);
        $total = $users;

        if($paramFilter){
            if ($first = $paramFilter["name"]) {
                $this->filter($users, 'a.name', $first);
            }

            if ($contact = $paramFilter["contact"]) {
                $this->filter($users, 'a.contact', $contact);
            }

            if ($email = $paramFilter["email"]) {
                $this->filter($users, 'a.email', $email);
            }

            if ($status = $paramFilter["status"]) {
                $this->filterStatus($users, 'b.status', $status);
            }
        }

        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["name", "contact", "email", "status"]) ? $paramTable["sort"]["field"] : "name";
            $properSort = ($retouch_sort == "status" ? "b." : "a.").$retouch_sort;
            $users = $users->orderBy($properSort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $users = $users->skip($offset)->take(10);
        }

        $users = $users->get();
        $quotient = floor($users->count() / $perPage);
        $reminder = ($users->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        return array(
            "meta"=> array(
                "page"=> $paramTable["pagination"]["page"] ?? 1,
                "pages"=> $pagesScold ?? $paramTable["pagination"]["pages"],
                "perpage"=> $perPage,
                "total"=> $total->count(),
                "rowIds"=> $totalIds,
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> array_map(function($user) use($request){
                return [
                    "id"=>$user['id'],
                    "image"=>$user['image'],
                    "name"=>$user['name'],
                    "contact"=>$user['contact'],
                    "email"=>$user['email'],
                    "status"=>$user['status'],
                ];
            }, $users->toArray())
        );
    }

    /**
     * 
     * Standard Filter
     * 
     */
    protected function filter($users, string $property, array $param)
    {
        $filter = $param["filter"];
        $values = $param["values"];

        if($values){
            foreach ($values as $key => $value) {
                if($key==0){
                    $users->whereRaw($property._to_sql_operator($filter, $value));
                }else{
                    $users->orWhereRaw($property._to_sql_operator($filter, $value));
                }
            }

            return;
        }else{
            if($filter == "empty"){
                $users->where($property, "=", null);
            }

            if($filter == "!empty"){
                $users->where($property, "!=", null);
            }

            return;
        }
    }

    /**
     * 
     * Custom Status: first_name and last_name as name
     * 
     */
    protected function filterStatus($users, string $property, array $param)
    {
        $filter = $param["filter"];
        switch ($param["filter"]) {
            case 'both':
                return;
                break;
            
            default:
                $users->where($property, '=', $filter);
                return;
                break;
        }

    }

    function query(Request $request){
        $request->session()->put("inst_query", $request->all());
    }

    function resend_invitation(Request $request) : JsonResponse
    {
        $user = $request->instructor;
        $provider = $request->provider;

        $find = Invitation::where([
            "type" => "instructor",
            "user_id" => $user,
            "provider_id"=>$provider,
            "status"=>"pending"
        ])->first();

        if($find){
            if(date("Y-m-d", strtotime($find->created_at)) == date("'Y-m-d'")){
                return response()->json(["message"=>"You can only resend invitation once a day."],422);
            }else{
                $provider = Provider::find($provider);
                $user = User::find($user);
                if($user){
                    Mail::to($user->email)->send(new ProviderInstructorInvitation($user, $provider));
                    return response()->json([],200);
                }else{
                    return response()->json(["message"=>"Unable to find user! Please try again later."],422);
                }
            }
        }

        Invitation::insert([
            "type" => "instructor",
            "user_id"=>$user,
            "provider_id"=>$provider,
            "created_at"=>date("Y-m-d H:i:s"),
            "created_by"=>Auth::user()->id,
            "status"=>"pending"
        ]);

        $provider = Provider::find($provider);
        $user = User::find($user);
        if($user){
    
            Mail::to($user->email)->send(new ProviderInstructorInvitation($user, $provider));
            return response()->json([],200);
        }

        return response()->json(["message"=>"Unable to find user! Please try again later."],422);
    }

    function change(Request $request){
        if($request->id){
            $user = Instructor::where(["user_id"=>$request->id, "provider_id"=>_current_provider()->id])->where("status", "!=", "delete")->first();
            if($user){
                $user->status = $user->status == "active" ? "inactive" : ($user->status == "inactive" ? "active" : $user->status);
                if($user->save()){
                    $request->session()->flash('success', "Status has been changed to {$user->status}");
                }else{
                    $request->session()->flash('error', 'Unable to change status. Please try again later.');
                }
            }else{
                $request->session()->flash('error', 'Unable to process. Please try again later.');
            }
        }else{
            $request->session()->flash('error', 'Unable to process. Please try again later.');
        }

        return redirect()->route('provider.instructors');
    }

    function delete(Request $request){
        if($request->id){
            $user = Instructor::where(["user_id"=>$request->id, "provider_id"=>_current_provider()->id])->where("status", "!=", "delete")->first();
            if($user){
                $name = User::find($user->user_id);
                $user->status = "delete";
                if($user->save()){
                    $request->session()->flash('success', "{$name->name} has been successfully removed from user's list.");
                }else{
                    $request->session()->flash('error', 'Unable to delete user. Please try again later.');
                }
            }else{
                $request->session()->flash('error', 'Unable to process. Please try again later.');
            }
        }else{
            $request->session()->flash('error', 'Unable to process. Please try again later.');
        }

        return redirect()->route('provider.instructors');
    }

    function invite(Request $request) : JsonResponse
    {
        $user_ids = $request->users;
        foreach ($user_ids as $id) {
            if($id == Auth::user()->id && Auth::user()->instructor == "active"){
                Instructor::insert([
                    "user_id" => $id, 
                    "provider_id" => _current_provider()->id,
                    "status" => "pending",
                    "created_by" => Auth::user()->id,
                    "created_at" => date("Y-m-d H:i:s"),
                ]);

                continue;
            }

            $user = User::find($id);
             /**
             * Invite as instructor
             * 
             */
            Instructor::insert([
                "user_id" => $id, 
                "provider_id" => _current_provider()->id,
                "status" => "pending",
                "created_by" => Auth::user()->id,
                "created_at" => date("Y-m-d H:i:s"),
            ]);
            
            
            Invitation::insert([
                "type" => "instructor",
                "user_id"=>$id,
                "provider_id"=>_current_provider()->id,
                "created_at"=>date("Y-m-d H:i:s"),
                "created_by"=>Auth::user()->id,
                "status"=>"pending"
            ]);
            Mail::to($user->email)->send(new ProviderInstructorInvitation($user, _current_provider()));
        }

        return response()->json([], 200);
    }

    function search(Request $request) : JsonResponse
    {
        $find = [];
        if($request->search){
            $find = User::select("id", "name", "image", "instructor")
                ->where(["status" => "active", "accreditor" => "none", "superadmin" => "none"])
                ->whereRaw("(username like '%{$request->search}%' or email like '%{$request->search}%' or name like '%{$request->search}%')")
                ->get()->toArray();

            $find = array_map(function($user){
                $user['instructor_details'] = Instructor::select("status")->where(["provider_id"=> _current_provider()->id, "user_id" => $user['id']])->first();

                return $user;
            }, $find);
        }

        return response()->json($find, 200);
    }
}

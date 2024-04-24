<?php

namespace App\Http\Controllers\Provider;

use App\{User, Provider, Logs, Co_Provider as COP, Invitation, Provider_Permission, Instructor};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;
use App\Mail\{ProviderUserInvitation, ProviderInstructorInvitation, ProviderNonUserInvitation};

use Response;
use Session;

class UserController extends Controller
{
    function users(Request $request) : JsonResponse
    {
        $request->session()->forget('user_query');
        return response()->json($this->paginatedQueryUsers($request));
    }

    protected function paginatedQueryUsers(Request $request)
    {   
        $co = COP::select("user_id as id", "role", "status")->where("provider_id", "=", $request->provider)
            ->where("status", "!=", "delete")->get();
        
        $total = $co->count();
        $totalIds = array_map(function($user){
            return $user['id'];
        }, $co->toArray());

        $paramFilter = $request->session()->get("user_query");
        $paramTable = $request->all();
        
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        $users = User::select("a.id", "a.image", "a.name", "b.role", "a.email", "a.contact", "b.status")
            ->from("users as a")->leftJoin("co_providers as b", "a.id", "b.user_id")
            ->where('b.status', '!=', 'delete')->where("b.provider_id", "=", $request->provider);
        if($paramFilter){
            if ($first = $paramFilter["name"]) {
                $this->filter($users, 'a.name', $first);
            }

            if ($role = $paramFilter["role"]) {
                $this->filter($users, 'b.role', $role);
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
            $paramTable["sort"]["field"] = ($paramTable["sort"]["field"]=="submitted_at" ? "created_at": $paramTable["sort"]["field"]);
            $properSort = (in_array($paramTable["sort"]["field"], ["role", "status"]) ? "b." : "a.").$paramTable["sort"]["field"];
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
                "page"=> $paramTable["pagination"]["page"] ?? '1',
                "pages"=> $paramTable["pagination"]["pages"] ?? $pagesScold,
                "perpage"=> $perPage,
                "total"=> $total,
                "rowIds"=> $totalIds,
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> array_map(function($user) use($request){
                $permissions = [];

                if(_my_provider_permission("users", "edit")){
                   $permissions = _reverse_generate_permissions($user['id'], $request->provider);
                }

                return [
                    "id"=>$user['id'],
                    "image"=>$user['image'],
                    "name"=>$user['name'],
                    "role"=>strtoupper($user['role']),
                    "contact"=>$user['contact'],
                    "email"=>$user['email'],
                    "status"=>$user['status'],
                    "permissions" => $permissions
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
        $request->session()->put("user_query", $request->all());
    }

    function resend_invitation(Request $request) : JsonResponse
    {
        $user = $request->user;
        $provider = $request->provider;

        $find = Invitation::where([
            "type" => "provider",
            "user_id" => $user,
            "provider_id"=>$provider,
            // "status"=>"pending"
            "status"=>"done"

        ])->first();

        if($find){
            if(date("Y-m-d", strtotime($find->created_at)) == date("'Y-m-d'")){
                return response()->json(["message"=>"You can only resend invitation once a day."],422);
            }else{
                $provider = Provider::find($provider);
                $user = User::find($user);
                if($user){
                    Mail::to($user->email)->send(new ProviderUserInvitation($user, $provider));
                    return response()->json([],200);
                }else{
                    return response()->json(["message"=>"Unable to find user! Please try again later."],422);
                }
            }
        }

        Invitation::insert([
            "type" => "provider",
            "user_id"=>$user,
            "provider_id"=>$provider,
            "created_at"=>date("Y-m-d H:i:s"),
            "created_by"=>Auth::user()->id,
            "status"=>"pending"
            // "status"=>"done"
        ]);

        $provider = Provider::find($provider);
        $user = User::find($user);
        if($user){
    
            Mail::to($user->email)->send(new ProviderUserInvitation($user, $provider));
            return response()->json([],200);
        }

        return response()->json(["message"=>"Unable to find user! Please try again later."],422);
    }

    function accept_user_invitation(Request $request){
        $user_id = $request->id;
        $provider = $request->url;
        
        $findP = Provider::where("url", "=", $provider)->first();
        if($findP){
            $inv = Invitation::where([
                "type" => "provider",
                "user_id"=>$user_id,
                "provider_id"=>$findP->id,
                "status"=>"pending"
            ])->first();

            if($inv){
                $inv->status = "done";
                COP::where(["user_id"=>$user_id, "provider_id"=>$findP->id, "status"=>"pending"])
                    ->update(["status"=>"active"]);
                
                $request->session()->flash("success", "Invitation has been accepted!");
                return redirect("/provider/session/{$findP->id}");
            }

            $request->session()->flash("warning", "Invitation invalid!");
            return redirect()->route("home");
        }

		return view('template.errors.404');
    }

    function accept_inst_invitation(Request $request){
        $user_id = $request->id;
        $provider = $request->url;
        $findUser = User::find($user_id);
        if($findUser && $findUser->instructor == "active"){
            $findP = Provider::where("url", "=", $provider)->first();
            
            if($findP){
                $inv = Invitation::where([
                    "type" => "instructor",
                    "user_id"=>$user_id,
                    "provider_id"=>$findP->id,
                    "status"=>"pending" 
                ])->first();
                if($inv){
                    $inv->status = "done";
                    $inv->save();
                    Instructor::where(["user_id"=>$user_id, "provider_id"=>$findP->id, "status"=>"pending"])
                        ->update(["status"=>"active"]);
            
                    Session::flash('success', "Invitation has been accepted! Visit provider {$findP->name}.");
                    
                    session(["session_provider_id"=>Provider::select("id")->find($findP->id)]);
                    if(Session::get("session_provider_id") && Auth::check()){

                        if(_my_provider_permission("courses","view")){
                            return redirect()->route("provider.courses");

                        }elseif(_my_provider_permission("webinars","view")){
                            return redirect()->route("provider.courses");

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
                    }else{
                        Session::flash('success', "Invitation has been accepted! Please login and visit provider {$findP->name}.");
                    }
                    
                } else{
                    Session::flash('warning', "Invitation invalid!");
                }
                return redirect()->route("home");
            }
        }else{
            switch ($findUser->instructor) {
                case 'pending':
                case 'in-review':
                    Session::flash('info', "Sorry! FastCPD Management is currently reviewing your instructor application. Thank you!");
                    return redirect()->route("home");
                    break;

                case 'inactive':
                    Session::flash('warning', "Sorry! Your instructor status is currently inactive.");
                    return redirect()->route("home");
                    break;

                case 'denied':
                    Session::flash('warning', "Sorry! Your instructor application has been denied. Please resubmit your application. Thank you!");
                    if(Auth::check()){
                        return redirect()->route('instructor.register');
                    }else{
                        return redirect()->route("home");
                    }
                    break;
                    
                
                default:
                    Session::flash('warning', "Sorry! You have to be registered as an INSTRUCTOR before accepting invites from providers as an instructor.");
                    if(Auth::check()){
                        return redirect()->route('instructor.register');
                    }else{
                        return redirect()->route("home");
                    }
                    break;
            }
        }

		return view('template.errors.404');
    }

    function change(Request $request){
        if($request->id){
            $user = COP::where(["user_id"=>$request->id, "provider_id"=>_current_provider()->id])->first();
            $user_info = User::find($user->user_id);
            if($user){
                $user->status = $user->status == "active" ? "inactive" : ($user->status == "inactive" ? "active" : $user->status);
                if($user->save()){
                    $request->session()->flash('success', "Status has been changed to {$user->status}");
                    if($user->status == "inactive"){
                        _send_notification_email($user_info->email,'instructor_provider_unassigned',$user->provider_id,$user->provider_id);
                    }
                    
                }else{
                    $request->session()->flash('error', 'Unable to change status. Please try again later.');
                }
            }else{
                $request->session()->flash('error', 'Unable to process. Please try again later.');
            }
        }else{
            $request->session()->flash('error', 'Unable to process. Please try again later.');
        }

        return redirect()->route('provider.users');
    }

    function delete(Request $request){
        if($request->id){
            $user = COP::where([
                "user_id"=>$request->id, 
                "provider_id"=>_current_provider()->id,
            ])->where("status", "!=", "delete")->first();
            if($user){
                $name = User::find($request->id);
                $user->status = "delete";
                if($user->save()){
                    _send_notification_email($name->email,'instructor_provider_unassigned',_current_provider()->id,_current_provider()->id);
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

        return redirect()->route('provider.users');
    }

    function invite(Request $request) : JsonResponse
    {
        $user_ids = $request->users;
        $permissions = $request->permissions;
        $instructor = $request->instructor;

        $modules = _generate_permissions($permissions, false);
        foreach ($user_ids as $id) {
            $user = User::find($id);
            
            /**
             * Merge data permission and user info
             * 
             */
            $set_permission = array_map( function($perm) use($id){
                return array_merge($perm, [
                    "user_id" => $id, 
                    "provider_id" => _current_provider()->id,
                    "created_by" => Auth::user()->id,
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }, $modules);
            
            /**
             * Delete & Save Co-Provider & Permissions
             * 
             */
            Provider_Permission::where([
                "user_id" => $id,
                "provider_id" => _current_provider()->id
            ])->delete();
            Provider_Permission::insert($set_permission);
            COP::insert([
                "role" => "co-admin",
                "status" => "pending",
                // "status" => "active",
                "user_id" => $id, 
                "provider_id" => _current_provider()->id,
                "created_by" => Auth::user()->id,
                "created_at" => date("Y-m-d H:i:s"),
            ]);
            Mail::to($user->email)->send(new ProviderUserInvitation($user, _current_provider()));
            Invitation::insert([
                "type" => "provider",
                "user_id"=>$id,
                "provider_id"=>_current_provider()->id,
                "created_at"=>date("Y-m-d H:i:s"),
                "created_by"=>Auth::user()->id,
                "status"=>"pending"
                // "status"=>"done"
            ]);

             /**
             * Invite as instructor
             * 
             */
            if($instructor){
                $findInst = Instructor::where([
                    "user_id" => $id, 
                    "provider_id" => _current_provider()->id,
                ])->where("status", "!=", "delete")->first();

                if($findInst){
                    // Ignore invitation
                }else{
                    Instructor::insert([
                        "user_id" => $id, 
                        "provider_id" => _current_provider()->id,
                        "status" => "pending",
                        "created_by" => Auth::user()->id,
                        "created_at" => date("Y-m-d H:i:s"),
                    ]);
                    Mail::to($user->email)->send(new ProviderInstructorInvitation($user, _current_provider()));
                    Invitation::insert([
                        "type" => "instructor",
                        "user_id"=>$id,
                        "provider_id"=>_current_provider()->id,
                        "created_at"=>date("Y-m-d H:i:s"),
                        "created_by"=>Auth::user()->id,
                        "status"=>"pending"
                    ]);
                }
            }

        }

        return response()->json([], 200);
    }

    function nonuser_invite(Request $request) : JsonResponse
    {
        $emails = $request->emails;
        foreach ($emails as $email) {
            Mail::to($email)->send(new ProviderNonUserInvitation(_current_provider()));
        }

        return response()->json([], 200);
    }

    function search(Request $request) : JsonResponse
    {
        $cop = COP::select("user_id as id")->where("provider_id", "=", _current_provider()->id)
            ->where("status", "!=", "delete")->groupBy("user_id")->get()->toArray();
            
        $find = [];
        if($request->search){
            $find = User::select("id", "name", "image")
                ->where(["status" => "active", "accreditor" => "none"])
                ->whereRaw("(username like '%{$request->search}%' or email like '%{$request->search}%' or name like '%{$request->search}%')")
                ->whereNotIn("id", $cop)->get()->toArray();
        }

        return response()->json($find, 200);
    }
    
    function change_permission(Request $request) : JsonResponse 
    {
        $id = $request->user;
        $permissions = $request->permissions;

        $modules = _generate_permissions($permissions, false);
        $user = User::find($id);
            
        /**
         * Merge data permission and user info
         * 
         */
        $set_permission = array_map( function($perm) use($id){
            return array_merge($perm, [
                "user_id" => $id, 
                "provider_id" => _current_provider()->id,
                "created_by" => Auth::user()->id,
                "created_at" => date("Y-m-d H:i:s"),
            ]);
        }, $modules);
        
        /**
         * Find & Save Co-Provider & Permissions
         * 
         */
        Provider_Permission::where([
            "user_id" => $id,
            "provider_id" => _current_provider()->id
        ])->delete();

        Provider_Permission::insert($set_permission);

        return response()->json([], 200);
    }
}

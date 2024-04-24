<?php

namespace App\Http\Controllers\User;

use App\{User, Role};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};

use App\Mail\{VerificationEmail};
use Response;

class UserController extends Controller
{

    function index(Request $request) : JsonResponse
    {
        return response()->json($this->paginatedQuery($request));
    }

    protected function paginatedQuery(Request $request)
    {   
        $total = User::all();
        $totalIds = array_map(function($user){
            return $user['id'];
        }, $total->toArray());

        $paramFilter = $request->session()->get("user_query");
        $paramTable = $request->all();

        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $users = User::select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name,
            users.first_name, users.last_name,
            users.email_verified_at, users.added_by, users.status, users.id, 
            users.position, users.contact, users.email, users.role, roles.role as role_name"
        ))->leftJoin("roles", "users.role", "roles.id")->leftJoin("users as added", "users.added_by", "added.id")
            ->where('users.status', '!=', 'delete');
            
        
        if($paramFilter){
            if ($first = $paramFilter["name"]["first"]) {
                $this->filter($users, 'users.first_name', $first);
            }

            if ($last = $paramFilter["name"]["last"]) {
                $this->filter($users, 'users.last_name', $last);
            }

            if ($role = $paramFilter["role"]) {
                $this->filter($users, 'roles.role', $role);
            }

            if ($position = $paramFilter["position"]) {
                $this->filter($users, 'users.position', $position);
            }

            if ($contact = $paramFilter["contact"]) {
                $this->filter($users, 'users.contact', $contact);
            }

            if ($email = $paramFilter["email"]) {
                $this->filter($users, 'users.email', $email);
            }

            if ($added_by = $paramFilter["added_by"]) {
                $this->filterAdded($users, 'users.added_by', $added_by);
            }

            if ($verified = $paramFilter["verified"]) {
                $this->filterVerify($users, 'users.email_verified_at', $verified);
            }

            if ($status = $paramFilter["status"]) {
                $this->filterStatus($users, 'users.status', $status);
            }
        }

        if(array_key_exists("sort", $paramTable)){
            if($paramTable["sort"]["field"]=="name"){
                $properSort = "CONCAT(users.first_name, users.last_name) ";
                $users = $users->orderByRaw($properSort.$paramTable["sort"]["sort"])
                    ->skip($offset)->take($paramTable["pagination"]["perpage"]);
            }else if($paramTable["sort"]["field"]=="added_by"){
                $properSort = "CONCAT(added.first_name, added.last_name) ";
                $users = $users->orderByRaw($properSort.$paramTable["sort"]["sort"])
                    ->skip($offset)->take($paramTable["pagination"]["perpage"]);
            }else if($paramTable["sort"]["field"]=="verified"){
                $properSort = "IF(users.email_verified_at, 'Verified', 'Not Verified')";
                $users = $users->orderByRaw($properSort.$paramTable["sort"]["sort"])
                    ->skip($offset)->take($paramTable["pagination"]["perpage"]);
            }else{
                $properSort = ($paramTable["sort"]["field"]=="role"? "roles." : "users.").$paramTable["sort"]["field"];
                $users = $users->orderBy($properSort, $paramTable["sort"]["sort"])
                    ->skip($offset)->take($paramTable["pagination"]["perpage"]);
            }
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
                "total"=> $users->count(),
                "rowIds"=> $totalIds,
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> array_map(function($user){
                return [
                    "id"=>$user['id'],
                    "name"=>$user['name'],
                    "role"=>$user['role'] == 0 ? null : $user['role_name'],
                    "position"=>$user['position'],
                    "contact"=>$user['contact'],
                    "email"=>$user['email'],
                    "status"=>$user['status'],
                    "verified"=>$user['email_verified_at'],
                    "added_by"=>User::find($user['added_by']) ?? 'System',
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
     * Custom Filter: first_name and last_name as name
     * 
     */
    protected function filterAdded($users, string $property, array $param)
    {

        $filter = $param["filter"];
        $values = $param["values"];

        if($values){
            foreach ($values as $key => $value) {
                $added = User::whereRaw("CONCAT(first_name, last_name)"._to_sql_operator($filter, $value))->first();

                if($key==0){
                    if($value=="System"){
                        $users->whereRaw($property._to_sql_operator($filter, $value));
                    }else{
                        if($added){
                            $users->whereRaw($property._to_sql_operator($filter, $added->id));
                        }
                    }
                }else{
                    if($value=="System"){
                        $users->orWhereRaw($property._to_sql_operator($filter, $value));
                    }else{
                        if($added){
                            $users->orWhereRaw($property._to_sql_operator($filter, $added->id));
                        }
                    }
                }
            }

            return;
        }else{
            if($filter == "empty"){
                $users->where($property, "=", null);
            }

            if($filter == "!empty"){
                $users->where($property, "=", null);
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

    /**
     * 
     * Custom Verified: first_name and last_name as name
     * 
     */
    protected function filterVerify($users, string $property, array $param)
    {
        $filter = $param["filter"];
        switch ($param["filter"]) {
            case 'both':
                return;
                break;

            case 'verified':
                $users->where($property, '!=', null);
                return;
                break;

            case '!verified':
                $users->where($property, '=', null);
                return;
                break;
        }

    }

    function query(Request $request){
        $request->session()->put("user_query", $request->all());
    }

    function store(Request $request) : JsonResponse{
        
        $valid_email = User::where("email", "=", $request->email)->first();
        if($valid_email){
            return response()->json('Email already registered in our system with '.$valid_email->first_name.' '.$valid_email->last_name);

        }else{
            $user = new User;

            $user->first_name = $request->first;
            $user->last_name = $request->last;
            $user->position = $request->position;
            $user->role = $request->role;
            $user->email = $request->email;
            $user->contact = $request->contact;

            // Comment if no email-verification
            $user->status = "inactive";
            // no email-verification
            // $user->status = "active";
            $user->added_by = Auth::user()->id;

            if($user->save()){
                // Comment if no email-verification
                $this->verification($user, $request->email);
                $request->session()->flash('success', $request->first.' is added to users!');

                //_logs("users", "New user {$request->first} {$request->last} has been added to our records!");
                return response()->json(200);

            }else{
                return response()->json('Unable to save user! Please try again later.');
            }
        }
    }

    function edit(Request $request) : JsonResponse{
        
        $valid_email = User::where("email", "=",$request->email)->where("id", "!=", $request->user)->first();
        if($valid_email){
            return response()->json('Email already registered in our system with '.$valid_email->first_name.' '.$valid_email->last_name);

        }else{
            $user = User::find($request->user);
            if($user){
                $user->first_name = $request->first;
                $user->last_name = $request->last;
                $user->position = $request->position;
                $user->role = $request->role;
                $user->email = $request->email;
                $user->contact = $request->contact;
                
                if($user->save()){
                    // Comment if no email-verification
                    $this->verification($user, $request->email);
                    $request->session()->flash('success', $request->first.' is updated!');

                    //_logs("users", "User {$request->first} {$request->last} has been updated to our records!");
                    return response()->json(200);

                }else{
                    return response()->json('Unable to update user! Please try again later.');
                }
            }else{
                return response()->json('Unable to find user! Please try again later.');
            }
        }
    }

    function change(Request $request){
        if($request->id){
            $user = User::find($request->id);
            if($user){
                $user->status = $user->status == "active" ? "inactive" : ($user->status == "inactive" ? "active" : $user->status);
                if($user->save()){
                    $request->session()->flash('success', $user->first_name.' '.$user->last_name.' has been changed to '.$user->status);
                    //_logs("users", "User {$user->first_name} {$user->last_name} has been changed to ".$user->status);
                }else{
                    $request->session()->flash('error', 'Unable to change status. Please try again later.');
                }
            }else{
                $request->session()->flash('error', 'Unable to process. Please try again later.');
            }
        }else{
            $request->session()->flash('error', 'Unable to process. Please try again later.');
        }

        return redirect()->route('users.list');
    }

    function delete(Request $request){
        if($request->id){
            $user = User::find($request->id);
            if($user){
                $user->status = "delete";
                if($user->save()){
                    $request->session()->flash('success', $user->first_name.' '.$user->last_name.' has been deleted');
                    //_logs("users", "User {$user->first_name} {$user->last_name} has been deleted");
                }else{
                    $request->session()->flash('error', 'Unable to delete user. Please try again later.');
                }
            }else{
                $request->session()->flash('error', 'Unable to process. Please try again later.');
            }
        }else{
            $request->session()->flash('error', 'Unable to process. Please try again later.');
        }

        return redirect()->route('users.list');
    }

    function verification($user, $email){
        Mail::to($email)->send(new VerificationEmail($user));
    }
}

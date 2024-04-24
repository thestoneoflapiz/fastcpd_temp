<?php

namespace App\Http\Controllers\Webinar;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, 
    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Instructor_Permission as WIP,
    Section, Video, Article, Quiz,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class InstructorController extends Controller
{
    function index(Request $request){
        $request->session()->forget('filter_instructor');
        if(_my_webinar_permission("instructors") && _webinar_creation_restricted("instructors")){
            $data = [
                'instructor_ids' => json_decode(_current_webinar()->instructor_id),
            ];
    
            return view('page/webinar/management/instructor', $data);
        }
        
        return view('template/errors/404');
    }

    function list(Request $request) : JsonResponse
    {  
        $id = _current_webinar()->id;
        $provider_id = _current_provider()->id;
        $instructor = $request->session()->get('filter_instructor');
        
        $total_records = 0;

        if($instructor){
            $instructor_list = User::select("users.id as user_id","users.name",
                    "webinar_instructor_permissions.id as permission_id","webinar_instructor_permissions.webinar_details","webinar_instructor_permissions.attract_enrollments","webinar_instructor_permissions.instructors",
                    "webinar_instructor_permissions.video_and_content","webinar_instructor_permissions.handouts","webinar_instructor_permissions.grading_and_assessment","webinar_instructor_permissions.submit_for_accreditation",
                    "webinar_instructor_permissions.price_and_publish","instructors.status")
                ->leftJoin("instructors","instructors.user_id","users.id")
                ->leftJoin("webinar_instructor_permissions","users.id","webinar_instructor_permissions.user_id")
                ->where("webinar_instructor_permissions.webinar_id",$id)
                ->where("instructors.provider_id",$provider_id)
                ->where("instructors.status", "!=", "delete");

            $total_records = $instructor_list->get()->count();
            if($instructor[0] == "all"){
                $instructor_list = $instructor_list->get();
            }else{
                $instructor_list = $instructor_list->whereIn("users.id",$instructor)->get();
            }

            $instructor_list = array_filter($instructor_list->toArray(), function($use) use($provider_id){
                $is_provider_inst = _is_provider_instructor($use["user_id"], $provider_id);
                if($is_provider_inst){
                    return [
                        "user_id" => $use['user_id'],
                        "webinar_id" => _current_webinar()->id,
                        "name" => $use['name'],
                        "webinar_details" => $use ? $use['webinar_details'] : 0,
                        "attract_enrollments" => $use ? $use['attract_enrollments'] : 0,
                        "instructor_permission" => $use ? $use['instructors'] : 0,
                        "video_and_content" => $use ? $use['video_and_content'] : 0 ,
                        "handouts" => $use ? $use['handouts'] : 0 ,
                        "grading_and_assessment" => $use ? $use['grading_and_assessment'] : 0 ,
                        "submit_for_accreditation" => $use ? $use['submit_for_accreditation'] : 0 ,
                        "price_and_publish" => $use ? $use['price_and_publish'] : 0 ,
                        "status" => $use['status'],
                        "co_provider" => _is_co_provider($use["user_id"], $provider_id) ? "disabled" : ""
                    ];
                }
            });
        }else{
            if($id){
                $webinar = Webinar::find($id);
                if($webinar->instructor_id){
                    $instructor_list = User::select(
                        "users.id as user_id","users.name",
                        "webinar_instructor_permissions.id as permission_id","webinar_instructor_permissions.webinar_details","webinar_instructor_permissions.attract_enrollments","webinar_instructor_permissions.instructors",
                        "webinar_instructor_permissions.video_and_content","webinar_instructor_permissions.handouts","webinar_instructor_permissions.grading_and_assessment","webinar_instructor_permissions.submit_for_accreditation",
                        "webinar_instructor_permissions.price_and_publish","instructors.status"
                    )
                    ->whereIn('instructors.user_id',json_decode($webinar->instructor_id))
                    ->where("webinar_instructor_permissions.webinar_id",$id)
                    ->where("instructors.provider_id",$provider_id)
                    ->where("instructors.status", "!=", "delete")
                    ->leftJoin("instructors","instructors.user_id","users.id")
                    ->leftJoin("webinar_instructor_permissions","webinar_instructor_permissions.user_id","users.id")
                    ->get();

                    $total_records = $instructor_list->count();
                    
                    $instructor_list = array_filter($instructor_list->toArray(), function($use) use($provider_id){
                        $is_provider_inst = _is_provider_instructor($use["user_id"], $provider_id);
        
                        if($is_provider_inst){
                            return [
                                "user_id" => $use['user_id'],
                                "webinar_id" => _current_webinar()->id,
                                "name" => $use['name'],
                                "webinar_details" => $use ? $use['webinar_details'] : 0,
                                "attract_enrollments" => $use ? $use['attract_enrollments'] : 0,
                                "instructor_permission" => $use ? $use['instructors'] : 0,
                                "video_and_content" => $use ? $use['video_and_content'] : 0 ,
                                "handouts" => $use ? $use['handouts'] : 0 ,
                                "grading_and_assessment" => $use ? $use['grading_and_assessment'] : 0 ,
                                "submit_for_accreditation" => $use ? $use['submit_for_accreditation'] : 0 ,
                                "price_and_publish" => $use ? $use['price_and_publish'] : 0 ,
                                "status" => $use['status'],
                                "co_provider" => _is_co_provider($use["user_id"], $provider_id) ? "disabled" : ""
                            ];
                        }
                    });
                }else{
                    $instructor_list = [];
                }
                
                if($instructor === 0){
                    $instructor_list = [];
                }
                
            }else{
                $instructor_list = [];
            }
        }
      
        $totalIds = array_map(function($user){
            return $user['user_id'];
        }, $instructor_list);

        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"] ?? 1;
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        $quotient = floor(count($instructor_list) / $perPage);
        $reminder = (count($instructor_list) % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        return response()->json(array(
            "meta"=> array(
                "page"=> $paramTable["pagination"]["page"] ?? '1',
                "pages"=> $paramTable["pagination"]["pages"] ?? $pagesScold,
                "perpage"=> $perPage,
                "total"=> $total_records,
                "rowIds"=> $totalIds,
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> $instructor_list,
        ));
    }

    function list_filter(Request $request) : JsonResponse
    {
        if($request->instructor){
            $provider_id = _current_provider()->id;
            if($request->instructor[0] == "all") {
                $instructor_list = Instructor::where("provider_id",$provider_id)->get();
                foreach($instructor_list as $list){
                        $permission = WIP::where(["webinar_id" => session('webinar_id'),"user_id" => $list->user_id])->first();
                        if(! $permission){
                                WIP::insert([
                                    "user_id" =>  $list->user_id,
                                    "webinar_id" => _current_webinar()->id,
                                    "webinar_details" => 1,
                                    "attract_enrollments" => 1,
                                    "instructors" => 0,
                                    "video_and_content" => 1,
                                    "handouts" => 1,
                                    "grading_and_assessment" => 1,
                                    "submit_for_accreditation" => 1,
                                    "price_and_publish" => 1,
                                    "created_by" => Auth::user()->id,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_by" => Auth::user()->id,
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                        }
                }
            }else{
                foreach($request->instructor as $perm ){
                    $permission = WIP::where(["webinar_id" => session('webinar_id'),"user_id" => $perm])->first();
                    if(! $permission){
                            if(_is_co_provider($perm) != false){
                                WIP::insert([
                                    "user_id" => $perm,
                                    "webinar_id" => _current_webinar()->id,
                                    "webinar_details" => 1,
                                    "attract_enrollments" => 1,
                                    "instructors" => 1,
                                    "video_and_content" => 1,
                                    "handouts" => 1,
                                    "grading_and_assessment" => 1,
                                    "submit_for_accreditation" => 1,
                                    "price_and_publish" => 1,
                                    "created_by" => Auth::user()->id,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_by" => Auth::user()->id,
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                            }else{
                                WIP::insert([
                                    "user_id" => $perm,
                                    "webinar_id" => _current_webinar()->id,
                                    "webinar_details" => 1,
                                    "attract_enrollments" => 1,
                                    "instructors" => 0,
                                    "video_and_content" => 1,
                                    "handouts" => 1,
                                    "grading_and_assessment" => 1,
                                    "submit_for_accreditation" => 0,
                                    "price_and_publish" => 0,
                                    "created_by" => Auth::user()->id,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_by" => Auth::user()->id,
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                            }
                            
                    }
                }
            }
        }else{
            $request->instructor = 0;

            if(!_is_co_provider(_current_provider()->id)){
                if(_is_provider_instructor(Auth::user()->id, _current_provider()->id)){
                    return response()->json(["status" => 500, "message" => "You can't remove all instructors! Please leave at least one(1) instructor record"]);
                }
            }
        }
        
        $request->session()->put("filter_instructor",$request->instructor);

        $id = _current_webinar()->id;
        $instructor_id = _current_webinar()->instructor_id;
        $collection =[];
        $removed =[];
        $added =[];
        $selected_instructors = $request->session()->get("filter_instructor");

        $webinar = Webinar::find($id);
        if($webinar){
            if($selected_instructors){
                if($selected_instructors[0] == "all"){
                    $get_users = Instructor::where("provider_id", _current_provider()->id)->get();
                    
                    $instructor = [];
                    foreach($get_users as $user){
                        $instructor[] = $user->id;
                    }
                }else{
                    $instructor = [$selected_instructors];
                }

                foreach($instructor as $new_ins){
                    if (!in_array($new_ins, $collection)){ 
                        $collection[] = $new_ins; 
                    }
                }
                /////////////////checks who has been removed
                if($webinar->instructor_id == null){

                    foreach($collection[0] as $inst_id){
                        $instructor_info = User::find($inst_id);
                        if($inst_id != Auth::user()->id){
                            _notification_insert(
                                "webinar_creation",
                                $inst_id,
                                $id,
                                "Webinar Invitation as an instructor",
                                "You have been invited to access ".$webinar->title." by " . Auth::user()->name,
                                "/provider/webinar/" . $id

                            );
                            _notification_insert(
                                "webinar_creation",
                                Auth::user()->id,
                                $id,
                                "Webinar Invitation to an instructor",
                                "You have invited ".$instructor_info->name." to access " . $webinar->title,
                                "/provider/webinar/" . $id
    
                            );
                            _send_notification_email($instructor_info->email,"instructor_webinar_invitation",$id,$instructor_info->id);
                        }else{
                            _notification_insert(
                                "webinar_creation",
                                Auth::user()->id,
                                $id,
                                "Webinar Invitation to an instructor",
                                "You have invited your account to access " . $webinar->title,
                                "/provider/webinar/" . $id
    
                            );
                        }
                        
                    }

                }else{
                    if(count($collection[0]) > count(json_decode($webinar->instructor_id))){
                        $added = array_diff($collection[0], json_decode($webinar->instructor_id) );
                        foreach($added as $inst_id){
                            $instructor_info = User::find($inst_id);
                            
                            if($inst_id != Auth::user()->id){
                                _notification_insert(
                                    "webinar_creation",
                                    $inst_id,
                                    $id,
                                    "Webinar Invitation as an instructor",
                                    "You have been invited to access ".$webinar->title." by " . Auth::user()->name,
                                    "/provider/webinar/" . $id
    
                                );
                                _notification_insert(
                                    "webinar_creation",
                                    Auth::user()->id,
                                    $id,
                                    "Webinar Invitation to an instructor",
                                    "You have invited ".$instructor_info->name." to access " . $webinar->title,
                                    "/provider/webinar/" . $id
        
                                );
                                _send_notification_email($instructor_info->email,"instructor_webinar_invitation",$id,$instructor_info->id);
                            }else{
                                _notification_insert(
                                    "webinar_creation",
                                    Auth::user()->id,
                                    $id,
                                    "Webinar Invitation to an instructor",
                                    "You have invited your account to access " . $webinar->title,
                                    "/provider/webinar/" . $id
        
                                );
                            }
                        }
                    }else{
                        $removed = array_diff(json_decode($webinar->instructor_id) ,$collection[0] );

                        foreach($removed as $inst_id){
                            $instructor_info = User::find($inst_id);

                            if($inst_id != Auth::user()->id){
                                _notification_insert(
                                    "webinar_creation",
                                    $inst_id,
                                    $id,
                                    "Unassigned instructor to webinar",
                                    "Your access to ".$webinar->title." has been edited." ,
                                    "/provider/webinar/" . $id
    
                                );
    
                                _notification_insert(
                                    "webinar_creation",
                                    Auth::user()->id,
                                    $id,
                                    "Unassigned instructor to webinar",
                                    "You've edited access of ".$instructor_info->name." to " . $webinar->title,
                                    "/provider/webinar/" . $id
        
                                );
                                // instructor_webinar_update
                                _send_notification_email($instructor_info->email,"instructor_webinar_unassigned",$id,$instructor_info->id);
                            }else{
                                _notification_insert(
                                    "webinar_creation",
                                    Auth::user()->id,
                                    $id,
                                    "Unassigned instructor to webinar",
                                    "You've edited your own access to " . _current_webinar()->title,
                                    "/provider/webinar/" . $id
                        
                                );
                            }
                        }

                    }

                }
                $webinar->instructor_id = json_encode($collection[0]);
                if ($webinar->save()){
                    return response()->json(["status" => 200, "message" => "Instructor added!"]);
                }
                return response()->json(["status" => 500, "message" => "Unable to save update! Please refresh your browser"]);
            }else{
                $webinar->instructor_id = null;
                if ($webinar->save()){
                    return response()->json(["status" => 200, "message" => "Instructor list updated!"]);
                }
                return response()->json(["status" => 500, "message" => "Unable to save update! Please refresh your browser"]);
            }
        }else{
            return response()->json(["status" => 500, "message" => "Webinar not Found!"]);
        }
    }

    function permission(Request $request) : JsonResponse
    {
        $status = 0;
        $id = _current_webinar()->id;
        if($request->status == "true"){
            $status = 1;
        }

        WIP::where('webinar_id', $id)
            ->where('user_id', $request->user_id)
            ->update([$request->column => $status]);
        $instructor_info = User::find($request->user_id);
        
        if($request->user_id != Auth::user()->id){
            //instructor
            _notification_insert(
                "webinar_creation",
                $request->user_id,
                $id,
                "Unassigned instructor to webinar",
                "Your access to "._current_webinar()->title." has been edited." ,
                "/provider/webinar/" . $id
    
            );
            
            _notification_insert(
                "webinar_creation",
                Auth::user()->id,
                $id,
                "Unassigned instructor to webinar",
                "You've edited access of ".$instructor_info->name." to " . _current_webinar()->title,
                "/provider/webinar/" . $id
    
            );
        }else{
            _notification_insert(
                "webinar_creation",
                Auth::user()->id,
                $id,
                "Unassigned instructor to webinar",
                "You've edited your own access to " . _current_webinar()->title,
                "/provider/webinar/" . $id
    
            );
        }

        return response()->json([]);
    }

    function action(Request $request) : JsonResponse
    {
        $id = _current_webinar()->id;
        $instructor_id = _current_webinar()->instructor_id;
        $collection =[];

        if($id){
            if($request->instructors){
                if($request->instructors[0] == "all"){
                    $get_users = Instructor::where("provider_id", _current_provider()->id)->get();
                    
                    $instructor = [];
                    foreach($get_users as $user){
                        $instructor[] = $user->id;
                    }
                }else{
                    $instructor = [$request->instructors];
                }
                if($instructor_id){
                    foreach(json_decode($instructor_id) as $ins){
                        $collection[] = $ins;
                    }
                }
                
                foreach($instructor as $new_ins){
                    if (!in_array($new_ins, $collection)){ 
                        $collection[] = $new_ins; 
                    } 
                }
                $webinar
                 = Webinar::find($id);
                $webinar->instructor_id = json_encode($collection);

                if($webinar->save()){
                    return response()->json(["status" => 200, "message" => "Instructor Added!"]);
                }

                return response()->json(["status" => 500, "message" => "Something went wrong!"]);
            }
        }else{
            return response()->json(["status" => 500, "message" => "Webinar not Found!"]);
        }
    }
}
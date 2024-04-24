<?php

namespace App\Http\Controllers\Superadmin;

use App\{User, Provider, Co_Provider, Instructor, Course,Purchase_Item,Profession, Webinar_Session};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;
use WebinarSessions;

class CourseController extends Controller
{
    function verification_list(Request $request) : JsonResponse
    {
        $in_review_courses = Course::select("courses.id","users.name as username","providers.name as prov_name", 
                                    "courses.url","courses.created_at","courses.title" )
                                ->where(["prc_status"=>"approved", "fast_cpd_status"=>"in-review"])
                                ->leftJoin("users","users.id","courses.created_by")
                                ->leftJoin("providers","providers.id","courses.provider_id")
                                ->get();
        if(count($in_review_courses)){
            foreach($in_review_courses as $course){
                $courses[]= [
                    "id" => $course->id,
                    "submitted_at" => date("M j, Y",strtotime($course->created_at)),                 
                    "submitted_by"=> $course->username,
                    "course" => $course->title,
                    "provider" => $course->prov_name,
                    "url" => $course->url,
                ];
            }
        }else{
            $courses= [];
        }
        
        
        return response()->json(["data"=>$courses, "total"=>count($courses)], 200);
    }
 
    function update_course(Request $request) : JsonResponse
    {
        $course_data = Course::find($request->course_id);
        $user_provider= User::where("provider_id",$course_data->provider_id)->get();
        if($request->state == "approve"){
            _notification_insert(
                "course_creation",
                $user_provider[0]->id,
                $course_data->id,
                "Course Submission for approved",
                " Your course ".$course_data->title."has been approved by FastCPD",
                "/course/". $course_data->url
    
            );
    
            foreach(json_decode($course_data->instructor_id) as $inst){
                _notification_insert(
                    "course_creation",
                    $inst,
                    $course_data->id,
                    "Course Submission for approve",
                    $course_data->title . " has been approved by FastCPD",
                    "/course/". $course_data->url
                );
            }

            if(date("Y-m-d",strtotime($course_data->session_start)) <= date("Y-m-d")){
                $course = Course::where("id",$request->course_id)
                    ->update([
                    "fast_cpd_status" => "live",
                ]);
                
            }else{
                $course = Course::where("id",$request->course_id)
                    ->update([
                    "fast_cpd_status" => "published",
                    "published_at" => date('Y-m-d H:i:s')
                ]);

                _notification_insert(
                    "course_creation",
                    $user_provider[0]->id,
                    $course_data->id,
                    "Course Submission for approved",
                    $course_data->title."is now published! Start sharing your course to other professionals",
                    "/course/". $course_data->url
        
                );
        
                foreach(json_decode($course_data->instructor_id) as $inst){
                    _notification_insert(
                        "course_creation",
                        $inst,
                        $course_data->id,
                        "Course Submission for approve",
                        $course_data->title . " is now published! Start sharing your course to other professionals",
                        "/course/". $course_data->url
                    );
                }
            }

            
            
        }else{
            _notification_insert(
                "course_creation",
                $user_provider[0]->id,
                $course_data->id,
                "Course Submission rejection",
                " You're almost done. We just have some concerns with ".$course_data->title,
                "/course/management"
    
            );
    
            $course = Course::where("id",$request->course_id)
                ->update([
                "fast_cpd_status" => "draft",
            ]);
        }
        
        return response()->json($request, 200); 
    }

    function report_list(Request $request) : JsonResponse
    {
       
        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
    
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        $provider_id = $request->provider_id;
        $type = $request->type;
        $provider = $request->provider;

        if($provider){
            if($provider[0] == 0){
                if($type == "video-on-demand"){
                    $courses = Provider::select(
                                    "providers.name as provider_name",
                                    "providers.id as provider_id",
                                    "courses.title as course_title",
                                    "courses.published_at as course_published_at",
                                    "courses.session_start as course_session_start",
                                    "courses.price as course_price",
                                    "courses.id as course_id",
                                    "courses.accreditation as accreditation",
                                    "courses.instructor_id as course_instructor"
                                )
                                ->where("courses.fast_cpd_status","live")->orWhere("courses.fast_cpd_status","published")
                                ->leftJoin("courses","providers.id","courses.provider_id");
                                $quotient = floor($courses->count() / $perPage);
                                $reminder = ($courses->count() % $perPage);
                                $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
                                $meta = array(
                                    "page"=> $paramTable["pagination"]["page"] ?? '1',
                                    "pages"=>  $pagesScold,
                                    "perpage"=> $perPage,
                                    "total"=> $courses->count(),
                                    "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                                    "field"=> $paramTable["sort"]["field"] ?? "provider_name",
                                );
                                $provider_courses = $courses->get();
                                $data = $this->video_demand_filter($provider_courses);
                }else{
                    $courses = Provider::select(
                                    "providers.name as provider_name",
                                    "providers.id as provider_id",
                                    "webinars.title as course_title",
                                    "webinars.published_at as course_published_at",
                                    "webinars.prices as course_price",
                                    "webinars.id as course_id",
                                    "webinars.accreditation as accreditation",
                                    "webinars.instructor_id as course_instructor"
                                )
                                ->where("webinars.fast_cpd_status","live")->orWhere("webinars.fast_cpd_status","published")
                                ->leftJoin("webinars","providers.id","webinars.provider_id");
                                $quotient = floor($courses->count() / $perPage);
                                $reminder = ($courses->count() % $perPage);
                                $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
                                $meta = array(
                                    "page"=> $paramTable["pagination"]["page"] ?? '1',
                                    "pages"=>  $pagesScold,
                                    "perpage"=> $perPage,
                                    "total"=> $courses->count(),
                                    "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                                    "field"=> $paramTable["sort"]["field"] ?? "provider_name",
                                );
                                $provider_courses = $courses->get();

                                $data = $this->webinar_filter($provider_courses);
                            
                }
                return response()->json(["data"=>$data,"meta"=>$meta], 200); 
            }else{
                
                if($type == "video-on-demand"){
                    $courses = Provider::select(
                                    "providers.name as provider_name",
                                    "providers.id as provider_id",
                                    "courses.title as course_title",
                                    "courses.published_at as course_published_at",
                                    "courses.session_start as course_session_start",
                                    "courses.price as course_price",
                                    "courses.id as course_id",
                                    "courses.accreditation as accreditation",
                                    "courses.instructor_id as course_instructor"
                                )
                                ->whereIn("providers.id",$provider)
                                ->where(function($query){
                                    $query->where("courses.fast_cpd_status","live")->orWhere("courses.fast_cpd_status","published");
                                })
                                ->leftJoin("courses","providers.id","courses.provider_id");
                                $quotient = floor($courses->count() / $perPage);
                                $reminder = ($courses->count() % $perPage);
                                $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
                                $meta = array(
                                    "page"=> $paramTable["pagination"]["page"] ?? '1',
                                    "pages"=>  $pagesScold,
                                    "perpage"=> $perPage,
                                    "total"=> $courses->count(),
                                    "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                                    "field"=> $paramTable["sort"]["field"] ?? "provider_name",
                                );
                                $provider_courses = $courses->get();
                                $data = $this->video_demand_filter($provider_courses);
                }else{
                    $courses = Provider::select(
                                    "providers.name as provider_name",
                                    "providers.id as provider_id",
                                    "webinars.title as course_title",
                                    "webinars.published_at as course_published_at",
                                    "webinars.prices as course_price",
                                    "webinars.id as course_id",
                                    "webinars.accreditation as accreditation",
                                    "webinars.instructor_id as course_instructor"
                                )
                                ->whereIn("providers.id",$provider)
                                ->where(function($query){
                                    $query->where("webinars.fast_cpd_status","live")->orWhere("webinars.fast_cpd_status","published");
                                })
                                ->leftJoin("webinars","providers.id","webinars.provider_id");
                                $quotient = floor($courses->count() / $perPage);
                                $reminder = ($courses->count() % $perPage);
                                $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
                                $meta = array(
                                    "page"=> $paramTable["pagination"]["page"] ?? '1',
                                    "pages"=>  $pagesScold,
                                    "perpage"=> $perPage,
                                    "total"=> $courses->count(),
                                    "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                                    "field"=> $paramTable["sort"]["field"] ?? "provider_name",
                                );
                                $provider_courses = $courses->get();

                                $data = $this->webinar_filter($provider_courses);
                            
                }
                return response()->json(["data"=>$data,"meta"=>$meta], 200); 
            }
        }else{
            return response()->json([],200);
        }
    }
    function provider_list(Request $request):JsonResponse{
        $providers = Provider::where("status","approved")->get();
        return response()->json(["data"=>$providers],200);
    }
    function filter_list(Request $request):JsonResponse{
        
        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
  
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $provider = explode(",",$request->provider);
        $type = $request->type;

        if($provider){
            if($provider[0] == 0){
                if($type == "video-on-demand"){
                    $courses = Provider::select(
                                    "providers.name as provider_name",
                                    "providers.id as provider_id",
                                    "courses.title as course_title",
                                    "courses.published_at as course_published_at",
                                    "courses.session_start as course_session_start",
                                    "courses.price as course_price",
                                    "courses.id as course_id",
                                    "courses.accreditation as accreditation",
                                    "courses.instructor_id as course_instructor"
                                )
                                ->where("courses.fast_cpd_status","live")->orWhere("courses.fast_cpd_status","published")
                                ->leftJoin("courses","providers.id","courses.provider_id");
                                $quotient = floor($courses->count() / $perPage);
                                $reminder = ($courses->count() % $perPage);
                                $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
                                $meta = array(
                                    "page"=> $paramTable["pagination"]["page"] ?? '1',
                                    "pages"=>  $pagesScold,
                                    "perpage"=> $perPage,
                                    "total"=> $courses->count(),
                                    "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                                    "field"=> $paramTable["sort"]["field"] ?? "provider_name",
                                );
                                $provider_courses = $courses->get();
                                $data = $this->video_demand_filter($provider_courses);
                }else{
                    $courses = Provider::select(
                                    "providers.name as provider_name",
                                    "providers.id as provider_id",
                                    "webinars.title as course_title",
                                    "webinars.published_at as course_published_at",
                                    "webinars.prices as course_price",
                                    "webinars.id as course_id",
                                    "webinars.accreditation as accreditation",
                                    "webinars.instructor_id as course_instructor"
                                )
                                ->where("webinars.fast_cpd_status","live")->orWhere("webinars.fast_cpd_status","published")
                                ->leftJoin("webinars","providers.id","webinars.provider_id");
                                $quotient = floor($courses->count() / $perPage);
                                $reminder = ($courses->count() % $perPage);
                                $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
                                $meta = array(
                                    "page"=> $paramTable["pagination"]["page"] ?? '1',
                                    "pages"=>  $pagesScold,
                                    "perpage"=> $perPage,
                                    "total"=> $courses->count(),
                                    "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                                    "field"=> $paramTable["sort"]["field"] ?? "provider_name",
                                );
                                $provider_courses = $courses->get();

                                $data = $this->webinar_filter($provider_courses);
                            
                }
                return response()->json(["data"=>$data,"meta"=>$meta], 200); 
            }else{
                
                if($type == "video-on-demand"){
                    $courses = Provider::select(
                                    "providers.name as provider_name",
                                    "providers.id as provider_id",
                                    "courses.title as course_title",
                                    "courses.published_at as course_published_at",
                                    "courses.session_start as course_session_start",
                                    "courses.price as course_price",
                                    "courses.id as course_id",
                                    "courses.accreditation as accreditation",
                                    "courses.instructor_id as course_instructor"
                                )
                                ->whereIn("providers.id",$provider)
                                ->where(function($query){
                                    $query->where("courses.fast_cpd_status","live")->orWhere("courses.fast_cpd_status","published");
                                })
                                ->leftJoin("courses","providers.id","courses.provider_id");
                                $quotient = floor($courses->count() / $perPage);
                                $reminder = ($courses->count() % $perPage);
                                $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
                                $meta = array(
                                    "page"=> $paramTable["pagination"]["page"] ?? '1',
                                    "pages"=>  $pagesScold,
                                    "perpage"=> $perPage,
                                    "total"=> $courses->count(),
                                    "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                                    "field"=> $paramTable["sort"]["field"] ?? "provider_name",
                                );
                                $provider_courses = $courses->get();
                                $data = $this->video_demand_filter($provider_courses);
                }else{
                    $courses = Provider::select(
                                    "providers.name as provider_name",
                                    "providers.id as provider_id",
                                    "webinars.title as course_title",
                                    "webinars.published_at as course_published_at",
                                    "webinars.prices as course_price",
                                    "webinars.id as course_id",
                                    "webinars.accreditation as accreditation",
                                    "webinars.instructor_id as course_instructor"
                                )
                                ->whereIn("providers.id",$provider)
                                ->where(function($query){
                                    $query->where("webinars.fast_cpd_status","live")->orWhere("webinars.fast_cpd_status","published");
                                })
                                ->leftJoin("webinars","providers.id","webinars.provider_id");
                                $quotient = floor($courses->count() / $perPage);
                                $reminder = ($courses->count() % $perPage);
                                $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
                                $meta = array(
                                    "page"=> $paramTable["pagination"]["page"] ?? '1',
                                    "pages"=>  $pagesScold,
                                    "perpage"=> $perPage,
                                    "total"=> $courses->count(),
                                    "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                                    "field"=> $paramTable["sort"]["field"] ?? "provider_name",
                                );
                                $provider_courses = $courses->get();

                                $data = $this->webinar_filter($provider_courses);
                            
                }
                return response()->json(["data"=>$data,"meta"=>$meta], 200); 
            }
        }else{
            return response()->json([],200);
        }
    }
    function webinar_filter($courses)
    {
        $data = array_map(function($provider){
           
            $instructor_list = array();
            $profession_list = array();
            $total_enrollees = Purchase_Item::where("data_id",$provider['course_id'])->where("type","webinar")
                                            ->where("payment_status","paid")
                                            ->count();
            if($provider['course_instructor']){
                $instructors = User::select("users.name as instructor_name")
                                    ->whereIn("instructors.id",json_decode($provider['course_instructor']))
                                    ->leftJoin("instructors","instructors.user_id","users.id")
                                    ->get();
                if($instructors){
                    foreach($instructors as $instructor){
                        array_push($instructor_list,[
                            "name" => $instructor->instructor_name,
                            "url" => "/instructor/".$instructor->instructor_name
                        ]);
                    }
                }
            }
            if($provider['accreditation']){
                $accreditation = json_decode($provider['accreditation']);
                $profession = Profession::where("id",$accreditation[0]->id)->first();
                array_push($profession_list,[
                    "profession" => $profession->title,
                    "units" => $accreditation[0]->units
                ]);
            }
            
           
            $session = Webinar_Session::where("webinar_id",$provider['course_id'])->first();
            $session_start = $session->session_date;
           
            
            return[
                'id' => $provider['course_id'],
                "provider_name" => $provider['provider_name'],
                "course_title" => $provider["course_title"],
                "course_published_at" => date("M. d, 'y",strtotime($provider["course_published_at"])),
                "start_date" =>  date("M. d, 'y",strtotime($session_start)),
                "price_with" => $provider["course_price"] != null ? json_decode($provider["course_price"])->with : 0.00,
                "price_without" => $provider["course_price"] != null ? json_decode($provider["course_price"])->without : 0.00,
                "enrollees_total" => $total_enrollees,
                "cpd_units" => $profession_list,
                "instructors" => $instructor_list
            ];
        },$courses->toArray());

        return $data;
    }
    function video_demand_filter($courses){
        $data = array_map(function($provider){
            $instructor_list = array();
            $profession_list = array();
            $total_enrollees = Purchase_Item::where("data_id",$provider['course_id'])->where("type","course")
                                            ->where("payment_status","paid")
                                            ->count();
            if($provider['course_instructor']){
                $instructors = User::select("users.name as instructor_name")
                                    ->whereIn("instructors.id",json_decode($provider['course_instructor']))
                                    ->leftJoin("instructors","instructors.user_id","users.id")
                                    ->get();
                if($instructors){
                    foreach($instructors as $instructor){
                        array_push($instructor_list,[
                            "name" => $instructor->instructor_name,
                            "url" => "/instructor/".$instructor->instructor_name
                        ]);
                    }
                }
            }
            if($provider['accreditation']){
                $accreditation = json_decode($provider['accreditation']);
                $profession = Profession::where("id",$accreditation[0]->id)->first();
                array_push($profession_list,[
                    "profession" => $profession->title,
                    "units" => $accreditation[0]->units
                ]);
            }
            
            
            
            return[
                'id' => $provider['course_id'],
                "provider_name" => $provider['provider_name'],
                "course_title" => $provider["course_title"],
                "course_published_at" => date("M. d, 'y",strtotime($provider["course_published_at"])),
                "course_session_start" =>  date("M. d, 'y",strtotime($provider["course_session_start"])),
                "course_price" => $provider["course_price"] != null ? $provider["course_price"] : 0.00,
                "enrollees_total" => $total_enrollees,
                "cpd_units" => $profession_list,
                "instructors" => $instructor_list
            ];
        },$courses->toArray());

        return $data;
    }
}

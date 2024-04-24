<?php

namespace App\Http\Controllers\Provider;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Profession, 
    Webinar, Webinar_Session,
    Webinar_Instructor_Permission as WIP,
    Section, Video, Article, Quiz,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class WebinarController extends Controller
{
    function index(){
        if(_my_provider_permission("webinars", "view")){
            $data = [
                "professions" => array_map(function($id){
                    return Profession::find($id);
                }, json_decode(_current_provider()->profession_id)),
            ];
            
            $webinars = null;
            if(_current_provider()){
                $webinars = Webinar::where("provider_id", _current_provider()->id)->first();
            }

            if($webinars){
                return view('page/webinar/portal/list', $data);
            }

            return view('page/webinar/portal/index', $data);
        }else{
            return view('template/errors/404');
        }
    }
    
    function add(Request $request) : JsonResponse
    {
        $provider_id = $request->provider_id;
        $title = $request->title;
        $url = $request->webinar_url;
        $professions = $request->profession;
        $offering = $request->offering;
        $event = $request->event;
        
        $this->validate($request, [
            'webinar_url' => 'required|unique:webinars,url',
        ]);

        $instructor_id[] = Auth::user()->id ;
        $webinar = new Webinar;
        $webinar->provider_id = $provider_id;
        $webinar->title = $title;
        $webinar->url = $url;
        $webinar->profession_id = json_encode($professions);
        $webinar->offering_units = $offering;
        $webinar->event = $event;
        $webinar->prc_status = "draft";
        $webinar->fast_cpd_status = "draft";
        
        $state = false;
        if(_is_provider_instructor(Auth::user()->id,_current_provider()->id)){
            $webinar->instructor_id = json_encode([(string)Auth::user()->id]);
            $state = true;
        }

        $webinar->created_at = date("Y-m-d H:i:s");
        $webinar->created_by = Auth::user()->id;
            
        if($webinar->save()){
            $request->session()->put('webinar_id', $webinar->id);
            $request->session()->put('session_webinar_id', $webinar->id);
            if($state){
                WIP::insert([
                    "user_id" =>  Auth::user()->id,
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
            }

            return response()->json(["id" => $webinar->id], 200);
        }

        return response()->json(["msg"=>"Unable to save webinar. Please try again later!"], 422);
    }

    function list(Request $request) : JsonResponse
    {   
        $provider_id = $request->provider_id;
        $pagination = $request->pagination;
        
        $order = $pagination["order"];
        $search = $pagination["search"];
        $page = $pagination["page"];
        $take = $pagination["take"];
        $offset = $page * $take;
        
        if(Auth::user()->provider_id != $provider_id){
            $total = Webinar::select("provider_id")->where(["provider_id"=>$provider_id, "deleted_at"=>null])
                ->whereIn("prc_status", ["draft", "in-review", "approved"])
                ->whereRaw("(JSON_CONTAINS(instructor_id, '\"".Auth::user()->id."\"') OR created_by = '".Auth::user()->id."')");

            $webinars = Webinar::select("id","provider_id","url","webinar_poster","title","prc_status","fast_cpd_status","created_at","created_by", "prices", "offering_units", "event", "type", "accreditation")
                ->where(["provider_id"=>$provider_id, "deleted_at"=>null])
                ->whereIn("prc_status", ["draft", "in-review", "approved"])
                ->whereRaw("(JSON_CONTAINS(instructor_id, '\"".Auth::user()->id."\"') OR created_by = '".Auth::user()->id."')");
        }else{
            $total = Webinar::select("provider_id")->where(["provider_id"=>$provider_id, "deleted_at"=>null])
                ->whereIn("prc_status", ["draft", "in-review", "approved"]);

            $webinars = Webinar::select("id","provider_id","url","webinar_poster","title","prc_status","fast_cpd_status","created_at","created_by", "prices", "offering_units", "event", "type", "accreditation")
                ->where(["provider_id"=>$provider_id, "deleted_at"=>null])
                ->whereIn("prc_status", ["draft", "in-review", "approved"]);
        }
        
        /**
         * Search title
         * 
         */
        if($search){
            $webinars = $webinars->where("title", "like", "%{$search}%");
            $total = $total->where("title", "like", "%{$search}%");
        }

        /**
         * status
         * 
         */
        switch ($pagination["status"]) {
            case 'draft':
                $webinars = $webinars->whereIn("fast_cpd_status", ['draft', 'in-review']);
                $total = $total->whereIn("fast_cpd_status", ['draft', 'in-review']);
                break;

            case 'published':
                $webinars = $webinars->whereIn("fast_cpd_status", ['approved', 'published', 'live']);
                $total = $total->whereIn("fast_cpd_status", ['approved', 'published', 'live']);
                break;

            case 'ended':
                $webinars = $webinars->where("fast_cpd_status", "=",'ended');
                $total = $total->where("fast_cpd_status", "=",'ended');
                break;
        }
        /**
         * Order BY
         * 
         */
        switch ($order) {
            case 'newest':
                $webinars = $webinars->orderBy("created_at", "desc");
                break;

            case 'oldest':
                $webinars = $webinars->orderBy("created_at", "asc");
                break;

            case 'from_a':
                $webinars = $webinars->orderBy("title", "asc");
                break;

            case 'from_z':
                $webinars = $webinars->orderBy("title", "desc");
                break;
            
            default: // fast_cpd_status
                $webinars = $webinars->orderBy("fast_cpd_status", "asc")->orderBy("prc_status", "asc");
                break;
        }

        /**
         * Pagination
         * 
         */

        $webinars = $webinars->take($take)->offset($offset)->get();
        $webinars = array_map(function($webinar){

            $current_month_progress = $this->current_month_progress($webinar["id"]);

            $webinar["total_progress"] = _webinar_progress($webinar["id"]);

            $current_month_progress = $this->current_month_progress($webinar["id"]);
            $webinar["total_earning_month"] = $current_month_progress['current_month_overview']->current_revenue != null ? $current_month_progress['current_month_overview']->current_revenue : 0;
            $webinar["total_enrollment_month"] = $current_month_progress['current_month_overview']->current_enrolled;
            $webinar["total_webinar_rating"] = $current_month_progress['current_rating'];

            $webinar["prices"] = json_decode($webinar["prices"]);
            return $webinar;
        }, $webinars->toArray());


        return response()->json(["data"=>$webinars, "total"=>$total->get()->count()], 200);
    }

    function suggestions(Request $request) : JsonResponse
    {

        $title = $request->title;
        $remove_unwanted = preg_replace("/[^a-zA-Z-0-9]/", "-", $title);
        $remove_duplicate = preg_replace('/([-])\\1+/', '$1', $remove_unwanted);
        $trim_dash = trim($remove_duplicate, "-");
        $trim_dash = strtolower($trim_dash);
        
        $random_addons = ["watch", "live", "new", "webinar", "learn", "best", "good"];
        $suggestions = [ $trim_dash, date('Y-').$trim_dash, $trim_dash.date('-Y')];
        $suggestions = array_map(function($value) use($random_addons){
            $find = Webinar::where("url", "=", $value)->first();
            if($find){
                $new_value = $random_addons[rand(0, 6)]."-{$value}";
                $find_new = Webinar::where("url", "=", $value)->first();

                if($find_new){
                    $random_rand = ["{$new_value}-".date("dm"), date("dm")."-{$new_value}"];
                    return $random_rand[rand(0,1)];
                }

                return $new_value;
            }

            return $value;
        }, $suggestions);
        
        
        return response()->json(["data" => $suggestions], 200);
    }

    function current_month_progress($webinar_id){
        $provider_id = _current_provider();
        $current_month_overview = Webinar::select(
                DB::raw('sum(purchase_items.provider_revenue) as current_revenue'),
                DB::raw('count(purchase_items.id) as current_enrolled')
            )->whereMonth("purchase_items.updated_at",date("m"))
            ->whereYear("purchase_items.updated_at",date("Y"))
            ->where("providers.id",$provider_id->id)
            ->where("webinars.id",$webinar_id)
            ->where("purchase_items.payment_status","paid")
            ->where("purchase_items.type","webinar")
            ->leftJoin("providers","providers.id","webinars.provider_id")
            ->leftJoin("purchase_items","purchase_items.data_id","webinars.id")
            ->leftJoin("users","users.id","purchase_items.user_id")->first();
        return [
            "current_rating" =>  _get_avg_rating("webinar", $webinar_id),
            "current_month_overview" => $current_month_overview
        ];
    }

    function convert(Request $request)
	{
        if($request->has("id")){

            $explode = explode(":", $request->id);
            $id = $explode[0];

            $webinar = Webinar::find($id);
            if($webinar && $webinar->fast_cpd_status=="ended"){
                $data = [
                    "professions" => array_map(function($id){
                        return Profession::find($id);
                    }, json_decode(_current_provider()->profession_id)),
                    "webinar" => $webinar,
                    "webinar_professions" => json_decode($webinar->profession_id)
                ];

                return view('page/webinar/convert/index', $data);
            }
        }

        return view('template/errors/404');
	}
}
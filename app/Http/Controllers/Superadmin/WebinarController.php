<?php

namespace App\Http\Controllers\Superadmin;

use App\{
    User, Provider, Co_Provider, Instructor, Profession,
    Webinar, Webinar_Session, Webinar_Series, 
    Request_Contact, Instructional_Design
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use App\Mail\{NotificationMail};

use Response; 
use Session;

class WebinarController extends Controller
{
    function verification_index(Request $request)
    {
        $request->session()->forget("verification_webinar_query"); 
        return view('page/superadmin/verification/webinar/index');
    }

    function verification_list(Request $request) : JsonResponse
    {
        $webinars = Webinar::select(
            "webinars.id", "webinars.title", "webinars.url", "webinars.offering_units as offering",
            "webinars.event", "providers.name as provider", "providers.status as provider_status",
            "webinars.updated_at", "webinars.type", "webinars.fast_cpd_status"
        )->join("providers", "providers.id", "=", "webinars.provider_id", "left")
        ->whereIn("webinars.fast_cpd_status", ["draft","approved", "published", "live", "in-review"]);
        
        $webinar_total = $webinars->get();
        
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        

        $paramFilter = $request->session()->get("verification_webinar_query"); 
        if($paramFilter){
            if ($field = $paramFilter["title"]) {
                $this->filter($webinars, 'webinars.title', $field);
            }

            if ($field = $paramFilter["updated_at"]) {
                $this->filter($webinars, 'webinars.updated_at', $field);
            }

            if ($field = $paramFilter["offering"]) {
                if($field["filter"]=="with"){
                    $webinars->where("webinars.offering_units", "=", "with");
                }else if($field["filter"]=="without"){
                    $webinars->where("webinars.offering_units", "=", "without");
                }elseif($field["filter"]=="both"){
                    $webinars->where("webinars.offering_units", "=", "both");
                }
            }

            if ($field = $paramFilter["provider"]) {
                $this->filter($webinars, 'providers.name', $field);
            }

            if ($field = $paramFilter["provider_status"]) {
                if($field["filter"]=="affiliated"){
                    $webinars->where("providers.status", "=", "approved");
                }else if($field["filter"]=="unaffiliated"){
                    $webinars->where("providers.status", "!=", "approved");
                }
            }

            if ($field = $paramFilter["type"]) {
                if($field["filter"]=="official"){
                    $webinars->where("webinars.type", "=", "official");
                }else if($field["filter"]=="unaffiliated"){
                    $webinars->where("webinars.type", "!=", "promotional");
                }
            }

            if ($field = $paramFilter["fast_cpd_status"]) {
                $webinars->where("webinars.fast_cpd_status", "=", $field["filter"]);
            }
        }

        if(array_key_exists("sort", $paramTable)){
            if(in_array($paramTable["sort"]["field"], ["provider", "provider_status"])){
                if($paramTable['sort']['field']=="provider"){
                    $properSort = "providers.name";
                }else{
                    $properSort = "providers.status";
                }
            }else{
                if($paramTable['sort']['field']=="offering"){
                    $properSort = "webinars.offering_units";
                }else{
                    $properSort = "webinars.{$paramTable['sort']['field']}";
                }
            }

            $webinars = $webinars->orderBy($properSort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $webinars = $webinars->orderBy("webinars.fast_cpd_status")->skip($offset)->take(10);
        }

        $webinars = $webinars->get();
        $quotient = floor($webinars->count() / $perPage);
        $reminder = ($webinars->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $webinar_total_ids = array_map(function($ids){
            return $ids["id"];
        }, $webinars->toArray());

        return response()->json(array(
            "meta"=> array(
                "page"=> $paramTable["pagination"]["page"] ?? '1',
                "pages"=> $paramTable["pagination"]["pages"] ?? $pagesScold,
                "perpage"=> $perPage,
                "total"=> $webinar_total->count(),
                "rowIds"=> count($webinar_total_ids),
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> array_map(function($webinar){
                $webinar["updated_at"] = date("M. d Y", strtotime($webinar["updated_at"]));
                $session = Webinar_Session::where([
                    "webinar_id" => $webinar["id"],
                    "deleted_at" => null,
                    "link" => null,
                ])->first();

                $webinar["provide_link"] = $session ? true : false;
                return $webinar;
            }, $webinars->toArray())
        ));
    }

    function verification_session(Request $request) : JsonResponse
    {
        $request->session()->put("verification_webinar_query", $request->all());
        return response()->json([]);
    }

    function verification_approve(Request $request) : JsonResponse
    {
        $webinar = Webinar::find($request->id);
        if($webinar){
            $provider = Provider::find($webinar->provider_id);
            $webinar->fast_cpd_status = "published";
            $webinar->updated_at = date("Y-m-d H:i:s");
            $webinar->type = $provider->status == "approved" ? "official" : "promotional";
            $webinar->save();

            if(date("Y-m-d", strtotime($webinar->published_date))==date("Y-m-d")){
                _notification_insert(
                    "webinar_creation",
                    $provider->created_by,
                    $webinar->id,
                    "Webinar Submission for approved",
                    $webinar->title."is now published! Start sharing your webinar to other professionals",
                    "/webinar/". $webinar->url
        
                );
        
                foreach(json_decode($course_data->instructor_id) as $inst){
                    _notification_insert(
                        "webinar_creation",
                        $inst,
                        $webinar->id,
                        "Webinar Submission for approve",
                        $webinar->title . " is now published! Start sharing your webinar to other professionals",
                        "/webinar/". $webinar->url
                    );
                }
            }else{
                _notification_insert(
                    "webinar_creation",
                    $provider->created_by,
                    $webinar->id,
                    "Webinar Submission for approved",
                    " Your webinar ".$webinar->title."has been approved by FastCPD",
                    "/webinar/". $webinar->url
                );
    
                foreach(json_decode($webinar->instructor_id) as $inst){
                    _notification_insert(
                        "webinar_creation",
                        $inst,
                        $webinar->id,
                        "Webinar Submission for approve",
                        $webinar->title . " has been approved by FastCPD",
                        "/webinar/". $webinar->url
                    );
                }
            }

            return response()->json([]);
        }

        return response()->json([
            "message" => "Webinar not found!"
        ], 422); 
    } 

    function verification_session_list(Request $request) : JsonResponse
    {
        $sessions = Webinar_Session::where([
            "webinar_id" => $request->id,
            "deleted_at" => null,
        ])->orderBy("session_date")->get();
        if($sessions && $sessions->count() > 0){
            return response()->json([
                "data" => $sessions
            ]);
        }

        return response()->json([], 422); 
    }

    function verification_session_save(Request $request) : JsonResponse
    {

        foreach($request->links as $link){
            $session = Webinar_Session::find($link["id"]);
            if($session){
                if($link["value"]){
                    $session->link = $link["value"];
                    $session->updated_at = date("Y-m-d H:i:s");
                    $session->save();
                }
            }
        }

        return response()->json([]); 
    }

    function verification_contacts(Request $request) : JsonResponse
    {
        $contacts = Request_Contact::where([
            "type" => "webinar",
            "data_id" => $request->id,
            "deleted_at" => null
        ])->get();

        return response()->json([
            "data" => $contacts
        ]);
    }

    function verification_draft(Request $request) : JsonResponse
    {
        $webinar = Webinar::find($request->id);
        if($webinar){
            $webinar->target_number_students = null;
            $webinar->assessment = null;
            $webinar->submit_accreditation_evaluation = null;
            $webinar->accreditation = null;
            $webinar->expenses_breakdown = null;
            $webinar->expense_breakdown = null;
            $webinar->prc_status = "draft";
            $webinar->fast_cpd_status = "draft";
            $webinar->published_at = null;
            $webinar->approved_at = null;
            $webinar->type = null;
            $webinar->updated_at = date("Y-m-d H:i:s");
            $webinar->save();

            Instructional_Design::where([
                "type" => "webinar",
                "data_id" => $webinar->id,
            ])->delete();

            Webinar_Session::where([
                "webinar_id" => $webinar->id
            ])->update([
                "link" => null,
            ]);

            $provider = Provider::select("email", "name")->find($webinar->provider_id);
            if($provider){
                $data = array(
                    'subject' => "Webinar {$webinar->title} sent back to DRAFT",
                    'body' => "
                    Hi {$provider->name},<br/>
                    FastCPD Management has re-drafted your webinar {$webinar->title}. The following details have been reset:<br>
                    <ul>
                        <li>Webinar Links</li>
                        <li>PRC Accreditation(If you have registered)</li>
                        <li>Publish Webinar</li>
                    </ul>
                    You can submit for review once the details are complete. Thank you!
                    ",
                    'recipient'=> "",
                    'link_button' => "",
                    'label_button' => "",
                );

                Mail::to($provider->email)->send(new NotificationMail($data)) ;
            }

            return response()->json([]);
        }

        return response()->json([
            "message" => "Webinar not found!"
        ], 422); 
    }

    /**
     * 
     * Standard Filter
     * 
     */
    protected function filter($webinars, string $property, array $param)
    {
        $filter = $param["filter"];
        $values = $param["values"];

        if($values){
            foreach ($values as $key => $value) {
                if($key==0){
                    if($property == "webinars.updated_at"){
                        $webinars->whereRaw("$property"._to_sql_operator($filter, date("Y-m-d", strtotime($value))));
                    }else{
                        $webinars->whereRaw($property._to_sql_operator($filter, $value));
                    }
                }else{
                    if($property == "webinars.updated_at"){
                        $webinars->whereRaw($property._to_sql_operator($filter, date("Y-m-d", strtotime($value))));
                    }else{
                        $webinars->orWhereRaw($property._to_sql_operator($filter, $value));
                    }
                }
            }

            return;
        }else{
            if($filter == "empty"){
                $webinars->where($property, "=", null);
            }

            if($filter == "!empty"){
                $webinars->where($property, "!=", null);
            }

            return;
        }
    }
}

<?php

namespace App\Http\Controllers\Provider;

use App\{
    User, Provider, Logs, Invitation, Provider_Permission, Instructor,
    Voucher, Course,Webinar
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;
use App\Mail\{ProviderInstructorInvitation};
use Illuminate\Support\Str;

use Response;
use Session;

class PromotionController extends Controller
{
    function index(Request $request){
        $request->session()->forget("provider_promotion_query");

        if(_my_provider_permission("promotions", "view")){
            $voucher = Voucher::where([
                "channel" => "provider_promo",
                "provider_id" => _current_provider()->id,
            ])->whereIn("status", ["upcoming", "active", "ended", "in-review", "rejected"])->get();

            $data = [
                "total" => $voucher->count(),
            ];
            
            return view('page/organization/promotions/index', $data);
        }else{
            return view('template/errors/404');
        }
    }

    function join(Request $request) : JsonResponse
    {
        if(_current_provider()){

            $provider = Provider::find(_current_provider()->id);
            $provider->allow_marketing = $request->join;
            if($provider->save()){
                return response()->json([], 200);
            }
        }

        return response()->json([], 422);
    }

    function form(Request $request)
    {
        $data = [];
        $voucher_id = $request->voucher_id ?? null;

        $data["voucher_id"] = $voucher_id;
        $data["voucher"] = [];
        $data["courses"] = Course::where([
            "provider_id" => _current_provider()->id,
            "deleted_at" => null,
        ])->whereIn("fast_cpd_status", ["published", "live"])->get();
        $data["webinars"] = Webinar::where([
            "provider_id" => _current_provider()->id,
            "deleted_at" => null,
        ])->whereIn("fast_cpd_status", ["published", "live"])->get();

        if($voucher_id){
            if(_my_provider_permission("promotions", "edit")){

                $data["voucher"] = Voucher::find($voucher_id);
                $data["voucher_data_ids"] = $data["voucher"]->data_id ? json_decode($data["voucher"]->data_id) : "selected";
                
                return view('page/organization/promotions/form', $data);
            }
        }

        if(_my_provider_permission("promotions", "add")){
            return view('page/organization/promotions/form', $data);
        }

        return view('template/errors/404');
    }

    function save(Request $request) : JsonResponse
    {
        $voucher_id = $request->voucher_id;
        $voucher_code = $request->voucher_code;

        if(Str::contains($voucher_code, 'FASTRV')){
            return response()->json([], 422);
        }
        
        $discount = $request->discount;
        $description = $request->description;
        $courses = $request->courses;
        $webinars = $request->webinars;
        $session_start = date("Y-m-d", strtotime($request->start_date));
        $session_end = date("Y-m-d", strtotime($request->end_date));
        $data_id= array(
            "courses" => $courses,
            "webinars" => $webinars 
        );
        $voucher = Voucher::find($voucher_id);
        if($voucher){
        }else{
            $voucher = new Voucher;
            $voucher->provider_id = _current_provider()->id;
            $voucher->created_by = Auth::user()->id;
        }

        $voucher->channel = "provider_promo";
        $voucher->data_id = json_encode($data_id);
        $voucher->voucher_code = $voucher_code;
        $voucher->discount = $discount;
        $voucher->description = $description;
        $voucher->session_start = $session_start;
        $voucher->session_end = $session_end;
        $voucher->status = "in-review";
        $voucher->type = "manual_apply";

        if($voucher->save()){
            return response()->json([]);
        }

        return response()->json([], 422);
    }

    function delete(Request $request)
    {
        $voucher = Voucher::find($request->id);
        $voucher->status = "delete";
        $voucher->save();

        Session::flash("success", "{$voucher->voucher_code} has been deleted!");
        return redirect("/provider/promotions");
    }

    function unique_(Request $request) : JsonResponse
    {
        $voucher_code = $request->code;
        $voucher = Voucher::where("voucher_code", "=", $voucher_code)->whereIn("status", ["active", "upcoming", "in-review", "rejected"]);
        
        if($request->voucherId){
            $voucher = $voucher->where("id", "!=", $request->voucherId);
        }

        $voucher = $voucher->first();
        if($voucher){
            return response()->json([], 422);
        }
        
        if(Str::contains($voucher_code, 'FASTRV')){
            return response()->json([], 422);
        }

        return response()->json([]);
    }

    function list(Request $request) : JsonResponse
    {
        return response()->json($this->paginatedQueryPromotions($request));
    }

    function session(Request $request) : JsonResponse
    {
        $request->session()->put("provider_promotion_query", $request->all());
        return response()->json([]);
    }

    protected function paginatedQueryPromotions(Request $request)
    {   
        $vouchers = Voucher::select(
            "vouchers.id", "vouchers.data_id", "vouchers.voucher_code", "vouchers.discount",
            "vouchers.description", "vouchers.session_start", "vouchers.session_end",
            "vouchers.status", "b.name as created_by"
        )
        ->join("users as b", "b.id", "=", "vouchers.created_by", "left")
        ->where([
            "vouchers.channel" => "provider_promo",
            "vouchers.provider_id" => _current_provider()->id,
        ])->whereIn("vouchers.status", ["upcoming", "active", "ended", "in-review", "rejected"]);
        
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
        $paramFilter = $request->session()->get("provider_promotion_query"); 
        if($paramFilter){
            if ($first = $paramFilter["voucher_code"]) {
                $this->filter($vouchers, 'vouchers.voucher_code', $first);
            }

            if ($first = $paramFilter["discount"]) {
                $this->filter($vouchers, 'vouchers.discount', $first);
            }

            if ($first = $paramFilter["start_date"]) {
                $this->filter($vouchers, 'vouchers.session_start', $first);
            }

            if ($first = $paramFilter["end_date"]) {
                $this->filter($vouchers, 'vouchers.session_end', $first);
            }

            if ($first = $paramFilter["created_by"]) {
                $this->filter($vouchers, 'b.name', $first);
            }

            if ($status = $paramFilter["status"]) {
                if($status["filter"]!="all"){
                    $vouchers->where("vouchers.status", "=", $status["filter"]);
                }
            }
        }

        $voucher_total = $vouchers;
        if(array_key_exists("sort", $paramTable)){
            $properSort = $paramTable["sort"]["field"]=="created_by" ? "b.name" : "vouchers.{$paramTable["sort"]["field"]}";
            $vouchers = $vouchers->orderBy($properSort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $vouchers = $vouchers->skip($offset)->take(10);
        }

        $vouchers = $vouchers->get();
        $quotient = floor($vouchers->count() / $perPage);
        $reminder = ($vouchers->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $voucher_total_ids = array_map(function($ids){
            return $ids["id"];
        }, $voucher_total->get()->toArray());

        return array(
            "meta"=> array(
                "page"=> $paramTable["pagination"]["page"] ?? '1',
                "pages"=> $paramTable["pagination"]["pages"] ?? $pagesScold,
                "perpage"=> $perPage,
                "total"=> $voucher_total->get()->count(),
                "rowIds"=> count($voucher_total_ids),
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> array_map(function($vc){

                $vc["session_start"] = date("M. d, Y", strtotime($vc["session_start"]));
                $vc["session_end"] = date("M. d, Y", strtotime($vc["session_end"]));
                return $vc;
            }, $vouchers->toArray())
        );
    }

    /**
     * 
     * Standard Filter
     * 
     */
    protected function filter($vouchers, string $property, array $param)
    {
        $filter = $param["filter"];
        $values = $param["values"];

        if($values){
            foreach ($values as $key => $value) {
                if($key==0){
                    if($property == "vouchers.session_start" || $property == "vouchers.session_end"){
                        $vouchers->whereRaw($property._to_sql_operator($filter, date("Y-m-d", strtotime($value))));
                    }else{
                        $vouchers->whereRaw($property._to_sql_operator($filter, $value));
                    }
                }else{
                    if($property == "vouchers.session_start" || $property == "vouchers.session_end"){
                        $vouchers->orWhereRaw($property._to_sql_operator($filter, date("Y-m-d", strtotime($value))));
                    }else{
                        $vouchers->orWhereRaw($property._to_sql_operator($filter, $value));
                    }
                }
            }

            return;
        }else{
            if($filter == "empty"){
                $vouchers->where($property, "=", null);
            }

            if($filter == "!empty"){
                $vouchers->where($property, "!=", null);
            }

            return;
        }
    }
}

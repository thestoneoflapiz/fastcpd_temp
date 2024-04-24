<?php

namespace App\Http\Controllers\Superadmin;

use App\{
    User, Provider, Co_Provider, Instructor, Course,
    Voucher, 
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use App\Mail\{RejectedVoucher, ApprovedVoucher};

use Response; 
use Session;
use Str;


class VoucherController extends Controller
{
    function settings_page(Request $request){
        return view('page/superadmin/purchase_setting/voucher/index');
    }

    function settings_form(Request $request)
    {
        $data = [];
        $voucher_id = $request->voucher_id ?? null;

        $data["voucher_id"] = $voucher_id;
        $data["voucher"] = [];
        if($voucher_id){
            $data["voucher"] = Voucher::find($voucher_id);
        }

        return view('page/superadmin/purchase_setting/voucher/form', $data);
    }

    function settings_save(Request $request) : JsonResponse
    {
        $voucher_id = $request->voucher_id;
        $voucher_code = $request->voucher_code;
        
        if(Str::contains($voucher_code, 'FASTRV')){
            return response()->json([], 422);
        }

        $discount = $request->discount;
        $description = $request->description;
        $type = $request->type;

        $session_start = date("Y-m-d", strtotime($request->start_date));
        $session_end = date("Y-m-d", strtotime($request->end_date));

        $voucher = Voucher::find($voucher_id);
        if($voucher){
        }else{
            $voucher = new Voucher;
            $voucher->created_by = Auth::user()->id;
        }

        $voucher->channel = "fast_promo";
        $voucher->voucher_code = $voucher_code;
        $voucher->discount = $discount;
        $voucher->description = $description;
        $voucher->type = $type;
        $voucher->session_start = $session_start;
        $voucher->session_end = $session_end;

        if($session_start > date("Y-m-d")){
            $voucher->status = "upcoming";
        }else{
            $voucher->status = "active";
        }

        if($voucher->save()){
            return response()->json([]);
        }

        return response()->json([], 422);
    }
    
    function settings_delete(Request $request)
    {
        $voucher = Voucher::find($request->id);
        $voucher->status = "delete";
        $voucher->save();

        Session::flash("success", "{$voucher->voucher_code} has been deleted!");
        return redirect("/superadmin/settings/vouchers");
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

    function verification_list(Request $request) : JsonResponse
    {
        $request->session()->forget("verification_voucher_query");
        return response()->json($this->paginatedRequestVerifications($request));
    }

    function settings_list(Request $request) : JsonResponse
    {
        $request->session()->forget("settings_voucher_query");
        return response()->json($this->paginatedRequestSettings($request));
    }

    function verification_reject(Request $request) : JsonResponse
    {
        $voucher_id = $request->voucher_id;
        $message = $request->message;

        $voucher = Voucher::find($voucher_id);
        if($voucher){

            $voucher->status = "rejected";
            $user = User::select("id", "name", "email")->find($voucher->created_by);
            if($user){
                Mail::to($user->email)->send(new RejectedVoucher($user, $voucher, $message));
            }

            _notification_insert(
                "voucher",
                $user->id,
                $voucher->id,
                "Voucher approve",
                " Your voucher ".$voucher->voucher_code." has been approved by FastCPD",
                "/provider/promotions"
    
            );

            $voucher->save();
            return response()->json([]);
        }
        return response()->json([], 422);
    }

    function verification_approve(Request $request) : JsonResponse
    {
        $voucher_id = $request->voucher_id;
        $voucher = Voucher::find($voucher_id);
        if($voucher){

            if($voucher->session_start <= date("Y-m-d")){
                $voucher->status = "active";
            }else{
                $voucher->status = "upcoming";
            }

            $user = User::select("id", "name", "email")->find($voucher->created_by);
            if($user){
                Mail::to($user->email)->send(new ApprovedVoucher($user, $voucher));
            }
            $course_data = Course::find($request->data_id);

            _notification_insert(
                "voucher",
                $user->id,
                $voucher->id,
                "Voucher approve",
                " Your voucher ".$voucher->voucher_code." has been approved by FastCPD",
                "/provider/promotions"
    
            );

            $voucher->save();
            return response()->json([]);
        }
        return response()->json([], 422);
    }

    function verification_session(Request $request) : JsonResponse
    {
        $request->session()->put("verification_voucher_query", $request->all());
        return response()->json([]);
    }

    function settings_session(Request $request) : JsonResponse
    {
        $request->session()->put("settings_voucher_query", $request->all());
        return response()->json([]);
    }

    protected function paginatedRequestVerifications(Request $request)
    {   
        $vouchers = Voucher::select(
            "vouchers.id", "vouchers.data_id", "vouchers.voucher_code", "vouchers.discount",
            "vouchers.description", "vouchers.session_start", "vouchers.session_end",
            "vouchers.status", "b.name as created_by"
        )
        ->join("users as b", "b.id", "=", "vouchers.created_by", "left")
        ->where([
            "vouchers.channel" => "provider_promo",
            "vouchers.status" => "in-review",
        ]);
        
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
        $paramFilter = $request->session()->get("verification_voucher_query"); 
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

    protected function paginatedRequestSettings(Request $request)
    {   
        $vouchers = Voucher::select(
            "vouchers.id", "vouchers.data_id", "vouchers.voucher_code", "vouchers.discount",
            "vouchers.description", "vouchers.session_start", "vouchers.session_end",
            "vouchers.status","vouchers.type", "b.name as created_by"
        )
        ->join("users as b", "b.id", "=", "vouchers.created_by", "left")
        ->where("vouchers.status", "!=", "delete")->where("vouchers.channel", "=", "fast_promo")->where("type", "!=", "rc_once_applied");
        
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
        $paramFilter = $request->session()->get("settings_voucher_query"); 
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

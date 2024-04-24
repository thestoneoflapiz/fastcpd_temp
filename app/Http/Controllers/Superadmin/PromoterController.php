<?php

namespace App\Http\Controllers\Superadmin;

use App\{
    User, Provider, Co_Provider, Instructor, Course, 
    Profession, Top_Profession,Voucher, Promoter
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class PromoterController extends Controller
{
    function promoter_list(Request $request) : JsonResponse
    {
        $request->session()->forget("promoter_pagination_query");
        return response()->json($this->paginatedPromoter($request));
    }

    protected function paginatedPromoter(Request $request)
    {   
        $promoters = Promoter::select("vouchers.voucher_code","promoters.name","promoters.email","promoters.status","promoters.id")
        ->join("vouchers", "vouchers.provider_id", "=", "promoters.id", "left")
        ->where([
            "vouchers.channel" => "promoter_promo",
        ]);
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
        $paramFilter = $request->session()->get("promoter_pagination_query"); 
        if($paramFilter){
            if ($first = $paramFilter["voucher_code"]) {
                $this->filter($promoters, 'voucher_code', $first);
            }

            if ($first = $paramFilter["name"]) {
                $this->filter($promoters, 'name', $first);
            }

            if ($first = $paramFilter["email"]) {
                $this->filter($promoters, 'email', $first);
            }

            if ($status = $paramFilter["status"]) {
                if($status["filter"]!="all"){
                    $promoters->where("status", "=", $status["filter"]);
                }
            }
        }

        $voucher_total = $promoters;
        if(array_key_exists("sort", $paramTable)){
            $properSort = $paramTable["sort"]["field"]=="created_by" ? "name" : "{$paramTable["sort"]["field"]}";
            $promoters = $promoters->orderBy($properSort, $paramTable["sort"]["sort"])
                ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            $promoters = $promoters->skip($offset)->take(10);
        }

        $promoters = $promoters->get();
        $quotient = floor($promoters->count() / $perPage);
        $reminder = ($promoters->count() % $perPage);
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
            "data"=> $promoters->toArray()
        );
    }

    function promoter_session(Request $request) : JsonResponse
    {
        $request->session()->put("promoter_pagination_query", $request->all());
        return response()->json([]);
    }

    function edit(Request $request)
    {
        $promoter = Promoter::select("vouchers.voucher_code","promoters.name","promoters.first_name","promoters.middle_name","promoters.last_name",
                    "promoters.email","promoters.status","vouchers.id as voucher_id","promoters.id as promoter_id","vouchers.discount")
                    ->where("promoters.id",$request->id)
                    ->join("vouchers", "vouchers.provider_id", "=", "promoters.id", "left")->first();
        $data = array(
            "promoter"=> $promoter
        );
        // Session::flash("success", "{$promoter->name} has been deleted!");
        return view('page/superadmin/promoter/edit', $data);
    }

    function delete(Request $request)
    {
        $promoter = Promoter::find($request->id);
        $promoter->status = "delete";
        $promoter->save();

        Session::flash("success", "{$promoter->name} has been deleted!");
        return redirect("/superadmin/promoters");
    }
    function save(Request $request) : JsonResponse
    {
        $data_id = [];
        $voucher_id = $request->voucher_id;
        $voucher_code = $request->voucher_code;
        $firstname = $request->firstname;
        $middlename = $request->middlename;
        $lastname = $request->lastname;
        $discount = $request->discount;
        $email = $request->email;
        $voucher = Voucher::find($voucher_id);
        if($voucher){
        }else{
            $voucher = new Voucher;
            $voucher->created_by = Auth::user()->id;
        }
        $promoter = new Promoter;
        $promoter->first_name = $firstname;
        $promoter->middle_name = $middlename;
        $promoter->last_name = $lastname;
        $promoter->name = $firstname . " " . $middlename ." " . $lastname ;
        $promoter->email = $email;
        $promoter->password = bcrypt($voucher_code . date('Y'));
        $promoter->status = "invited";
        $promoter->created_by = Auth::user()->id;
        $promoter->created_at = date('Y-m-d H:i:s');
        $promoter->save();
        $data_id[] = $promoter->id;
        $voucher->channel = "promoter_promo";
        $voucher->voucher_code = $voucher_code;
        $voucher->provider_id = $promoter->id;
        $voucher->data_id = json_encode($data_id);
        $voucher->discount = $discount;
        $voucher->description = "This Voucher is dedicated for ". $firstname . " " . $middlename ." " . $lastname ." with ".$discount."% discount" ;
        $voucher->type = "manual_apply";
        $voucher->status = "upcoming";
        if($voucher->save()){
            _send_notification_email($email, "promoter_invitation", $promoter->id, $voucher_code);
            return response()->json([], 200);
        }

        return response()->json([], 422);
        
    }

    function edit_save(Request $request) : JsonResponse
    {
        $voucher_id = $request->voucher_id;
        $voucher_code = $request->voucher_code;
        $firstname = $request->firstname;
        $middlename = $request->middlename;
        $lastname = $request->lastname;
        $discount = $request->discount;
        $email = $request->email;
        $discount = $request->discount;
        $promoter_id = $request->promoter_id;
        $voucher = Voucher::where('id',$voucher_id)
                    ->update([
                        'discount' => $discount,
                        'voucher_code' => $voucher_code
                    ]);
        $promoter = Promoter::where('id',$promoter_id)
                    ->update([
                        'name' => $firstname . " " . $middlename ." " . $lastname,
                        'first_name' => $firstname,
                        'middle_name' => $middlename,
                        'last_name' => $lastname,
                        'email' => $email,
                    ]);
        
        return response()->json([], 200);

        
    }
}
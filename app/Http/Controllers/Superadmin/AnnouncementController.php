<?php

namespace App\Http\Controllers\Superadmin;

use App\{User, Provider, Co_Provider, Instructor, Course, SuperadminPermission, Announcement};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use App\Mail\{SuperadminInvitation};

use Response; 
use Session;


class AnnouncementController extends Controller
{
    function list(Request $request) : JsonResponse
    {
        return response()->json($this->paginatedRequestAnnouncement($request));
    }

    function announcement_query_session(Request $request) : JsonResponse
    {
        $request->session()->put("announcement_query", $request->all());
        return response()->json([]);
    }

    function paginatedRequestAnnouncement(Request $request)
    {

        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        // $perPage = $perPage < 10 ? 10 : $perPage;
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $announcement = Announcement::select(
            DB::raw("DATE_FORMAT(start_date, \"%b. %d, %Y %h:%i %p\") as start_date, DATE_FORMAT(end_date, \"%b. %d, %Y %h:%i %p\") as end_date, id, target_audience, title, status")
        )->where("status", "!=", "deleted");
      
        $announce_total = $announcement->get()->count();

        $paramFilter = $request->session()->get("announcement_query"); 
        if($paramFilter){
            if ($first = $paramFilter["title"]) {
                $this->filter($announcement, 'title', $first);
            }

            if ($first = $paramFilter["target_audience"]) {
                $this->filter($announcement, 'target_audience', $first);
            }

            if ($first = $paramFilter["start_date"]) {
                $this->filter($announcement, 'start_date', $first);
            }

            if ($first = $paramFilter["end_date"]) {
                $this->filter($announcement, 'end_date', $first);
            }

            if ($first = $paramFilter["status"]) {
                
                if($first["filter"]!="both"){
                    $announcement->where("status", "=", $first["filter"]);
                }

            }
        }

        if(array_key_exists("sort", $paramTable)){
            $announcement = $announcement->orderBy($paramTable["sort"]["field"], $paramTable["sort"]["sort"])
            ->skip($offset)->take($perPage);

        }else{
            $announcement = $announcement->skip($offset)->take(10);
        }
        
        $announcement = $announcement->get();
        $quotient = floor($announcement->count() / $perPage);
        $reminder = ($announcement->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $announce_total_ids = array_map(function($ids){
            return $ids["id"];
        }, $announcement->toArray());

        return array(
            "meta"=> array(
                "page"=> $paramTable["pagination"]["page"] ?? '1',
                "pages"=> $paramTable["pagination"]["pages"] ?? $pagesScold,
                "perpage"=> $perPage,
                "total"=> $announce_total,
                "rowIds"=> $announce_total_ids,
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> array_map(function($vc){
                // $vc["start_date"] = date("M. d, Y h:i A", strtotime($vc["start_date"]));
                // $vc["end_date"] = date("M. d, Y h:i A", strtotime($vc["end_date"]));
                return $vc;
            }, $announcement->toArray())
        );
    }

    function save(Request $request) : JsonResponse
    {
        $default_status = "pending";
        if($request->start_date == null){
            $announcement_check = Announcement::where('target_audience',$request->target_audience)
            ->where('status',"active")
            ->get();
            if(count($announcement_check)){
                
            }else{
                $default_status = "active";
            }
        }

        $announcement = new Announcement;
        $announcement->target_audience = $request->target_audience;
        $announcement->recipients = json_encode(["all"]);
        $announcement->message = $request->message;
        $announcement->banner_state = $request->banner_state;
        $announcement->title = $request->title;
        $announcement->start_date = $request->start_date;
        $announcement->end_date = $request->end_date;
        $announcement->status = $default_status;
        $announcement->created_by = Auth::user()->id;
        $announcement->created_at = date('Y-m-d H:i:s');
        if($announcement->save()){
            return response()->json([], 200);
        }
        return response()->json([], 422);
    }

    function delete(Request $request)
    {
        $announce = Announcement::find($request->id);
        $announce->status = "deleted";
        $announce->deleted_at = date("Y-m-d H:i:s");
        $announce->save();

        return redirect()->route('superadmin.announcements.index');
    }
    function change_status(Request $request)
    {
        $announce = Announcement::find($request->id);
        if($announce->status == "inactive"){
            $active_announce = Announcement::where('target_audience',$announce->target_audience)
                            ->where('status', 'active')->first();
            if($active_announce){
                $active_announce->status = "inactive";
                $active_announce->save();            
                Session::flash("success", "Successfully changed,BUT! announcement ".$active_announce->title." is now inactive");
                $announce->status = "active";
                $announce->save();
            }else{
                       
                Session::flash("success", "Successfully changed");
                $announce->status = "active";
                $announce->save();

            }
            
        }elseif($announce->status == "active"){
            Session::flash("success", "Successfully changed");
            $announce->status = "inactive";
            $announce->save();
        }elseif($announce->status == "expired" || $announce->status == "deleted"){
            Session::flash("info", "Sorry, this announcement may be deleted or expired");
            
        }

        return redirect()->route('superadmin.announcements.index');
    }

    protected function filter($announcement, string $property, array $param)
    {
        $filter = $param["filter"];
        $values = $param["values"];
        
        if($values){
            foreach ($values as $key => $value) {
                if($key==0){
                    // if($property == "start_date" || $property == "end_date"){
                    //     $announcement->whereRaw("DATE_FORMAT({$property}, \"%b. %d, %Y %h:%i %p\")"._to_sql_operator($filter, $value));
                    // }else{
                        $announcement->whereRaw($property._to_sql_operator($filter, $value));
                    // }
                }else{
                    // if($property == "start_date" || $property == "end_date"){
                    //     $announcement->whereRaw("DATE_FORMAT({$property}, \"%b. %d, %Y %h:%i %p\")"._to_sql_operator($filter, $value));
                    // }else{
                        $announcement->orWhereRaw($property._to_sql_operator($filter, $value));
                    // }
                }
            }

            return;
        }else{
            if($filter == "empty"){
                $announcement->where($property, "=", null);
            }

            if($filter == "!empty"){
                $announcement->where($property, "!=", null);
            }

            return;
        }
    }
    
}

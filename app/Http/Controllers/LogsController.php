<?php

namespace App\Http\Controllers;

use App\{Logs, User};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

class LogsController extends Controller
{
    function logs(Request $request){
        $request->session()->forget('logs_query');
        return view("page.logs.index");
    }

    function getLogs(Request $request) : JsonResponse{
        return response()->json($this->paginatedQueryLogs($request));
    }

    protected function paginatedQueryLogs(Request $request)
    {   
        $paramFilter = $request->session()->get("logs_query");
        $paramTable = $request->all();

        $page = $paramTable["pagination"]["page"];
        $perpage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * ($perpage);       
        
        if(array_key_exists("sort", $paramTable)){
            $logs = Logs::orderBy($paramTable["sort"]["field"], $paramTable["sort"]["sort"]);
        }else{
            $logs = Logs::orderBy("created_at", "desc");
        }

        if($paramFilter){
            if ($module = $paramFilter["module"]) {
                $this->filter($logs, 'module', $module, false);
            }

            if ($activity = $paramFilter["activity"]) {
                $this->filter($logs, 'activity', $activity, false);
            }

            if ($created_at = $paramFilter["created_at"]) {
                $this->filter($logs, 'created_at', $created_at, true);
            }

            if ($by = $paramFilter["by"]) {
                $this->filterAdded($logs, 'by', $by);
            }
        }

        $logs_pure_total = $logs->get()->count();
        $logs = $logs->skip($offset)->take($perpage)->get();
        $quotient = floor($logs_pure_total / 10);
        $reminder = ($logs_pure_total % 10);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
        return array(
            "meta"=> array(
                "page"=> $paramTable["pagination"]["page"] ?? '1',
                "pages"=> $paramTable["pagination"]["pages"] ?? $pagesScold,
                "perpage"=> $perpage,
                "total"=> $logs_pure_total,
                "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                "field"=> $paramTable["sort"]["field"] ?? "id",
            ),
            "data"=> array_map(function($logs){
                $by = User::find($logs["by"]);
                return [
                    "id"=>$logs['id'],
                    "module"=>$logs['module'],
                    "activity"=>$logs['activity'],
                    "by"=> $logs['by']=="System" ? $logs["by"] : ($by->first_name." ".$by->last_name) ?? "[undefined]",
                    "created_at"=>date('M. d Y h:i A', strtotime($logs['created_at'])),
                ];
            }, $logs->toArray())
        );
    }

    /**
     * 
     * Standard Filter
     * 
     */
    protected function filter($users, string $property, array $param, bool $date)
    {
        $filter = $param["filter"];
        $values = $param["values"];

        if($values){
            if($date){
                foreach ($values as $key => $value) {
                    if($key==0){
                        $users->whereRaw(" DATE_FORMAT({$property}, '%b. %d %Y %h:%i %p') "._to_sql_operator($filter, $value));
                    }else{
                        $users->orWhereRaw(" DATE_FORMAT({$property}, '%b. %d %Y %h:%i %p') "._to_sql_operator($filter, $value));
                    }
                }

                return;
            }
            
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
                        $users->whereRaw("`{$property}`"._to_sql_operator($filter, $added->id ?? null));
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

    function logs_query(Request $request){
        $request->session()->put("logs_query", $request->all());
    }

}

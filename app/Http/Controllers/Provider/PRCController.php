<?php

namespace App\Http\Controllers\Provider;

use App\{User, Provider, Logs, Co_Provider, Provider_Permission,Purchase_Item,CompletionReport, Webinar_Attendance, Webinar_Session};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Response;
use Session;

class PRCController extends Controller
{
    function list(Request $request) : JsonResponse
    {   
        /**
         * Fake Data
         * 
         * 
         */
        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
        $paramFilter = $request->session()->get('payouts_query');
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
       $month_year = $request->month;
       $course = $request->course;
      
       
        $reports = CompletionReport::select(
                                       "courses.title as course",
                                        "completion_reports.completion_date as date_period",
                                        "completion_reports.participants as participants",
                                        "completion_reports.id as completion_id"
                                    )
                                    ->where("completion_reports.provider_id",Auth::user()->provider_id)
                                    ->leftJoin("courses","courses.id","completion_reports.data_id");
       
        if($month_year != null && $course != null){
          
                $month = explode('-',$request->month)[0];
                $year = explode('-',$request->month,2)[1];
                $reports = $reports->whereMonth("completion_reports.completion_date",$month)
                                ->whereYear("completion_reports.completion_date",$year);
                if($course != 0){
                    $reports = $reports->where("completion_reports.data_id",$course);
                }
        }else{
            if($month_year != null){
                $month = explode('-',$request->month)[0];
                $year = explode('-',$request->month,2)[1];
                $reports = $reports->whereMonth("completion_reports.completion_date",$month)->whereYear("completion_reports.completion_date",$year);
            }
            if($course != null){
                if($course != 0){
                    $course = $request->course;
                    $reports = $reports->where("completion_reports.data_id",$course);
                }
            }
            
        }

        $quotient = floor($reports->count() / $perPage);
        $reminder = ($reports->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $reports->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "purchase_date",
        );

        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["date_period", "participants","course"]) ? $paramTable["sort"]["field"] : "date_period";
            $reports = $reports->orderBy($retouch_sort, $paramTable["sort"]["sort"])
            ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            if($perPage){
                $reports = $reports->skip($offset)->take($perPage);  
            }else{
                $reports = $reports->skip($offset)->take(10);  
            }
           
             
        }
        $reports = $reports->get();

       
        $data = array_map(function($report) use($request){
            return [
                "date_period" => date("F Y",strtotime($report['date_period'])),
                "participants" => $report['participants'],
                "id" => $report['completion_id'],
                "course" => $report['course'],
                "attendance_id" => $report["completion_id"],
            ];
        },$reports->toArray());
    
        return response()->json(["data"=>$data,"meta" => $meta, "total"=>count($data)], 200);
    }
    function webinarList(Request $request) : JsonResponse
    {   
        /**
         * Fake Data
         * 
         * 
         */
        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
        $paramFilter = $request->session()->get('payouts_query');
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
        $webinar = $request->webinar;
      
       
        $reports = Webinar_Session::select(
                                "webinar_sessions.webinar_id as webinar_id","webinar_sessions.id as session_id","webinar_sessions.session_date as session_date",
                                "webinar_sessions.sessions","webinars.title as title","webinar_sessions.id as webinar_session_id"
                                )->
                        where("webinar_sessions.webinar_id",$webinar)->leftJoin("webinars","webinars.id","webinar_sessions.webinar_id");
      

        $quotient = floor($reports->count() / $perPage);
        $reminder = ($reports->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $reports->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "session_date",
        );

        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["session_date", "participants","webinar"]) ? $paramTable["sort"]["field"] : "session_date";
            $reports = $reports->orderBy($retouch_sort, $paramTable["sort"]["sort"])
            ->skip($offset)->take($paramTable["pagination"]["perpage"]);
        }else{
            if($perPage){
                $reports = $reports->skip($offset)->take($perPage);  
            }else{
                $reports = $reports->skip($offset)->take(10);  
            }
           
             
        }
        $reports = $reports->get();

       
        $data = array_map(function($report) use($request){
            $participants = Webinar_Attendance::where([ "webinar_id" => $report["webinar_id"],"session_id" => $report["webinar_session_id"]])->count();
            // dd($participants);
            return [
                "session_date" => date("F d, Y",strtotime($report['session_date'])),
                "participants" => $participants,
                "id" => $report['webinar_session_id'],
                "webinar_id" => $report["webinar_id"],
                "webinar" => $report['title'],
                "attendance_id" => $report["webinar_session_id"],
            ];
        },$reports->toArray());
    
        return response()->json(["data"=>$data,"meta" => $meta, "total"=>count($data)], 200);
    }
    function setMonthYearList(Request $request):JsonResponse{

        $set_of_months = $this->generateMonthYear();
    
        return response()->json(["month_year"=>$set_of_months],200);
    }

    function generateMonthYear(){
        $monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        $year = CompletionReport::where("provider_id",Auth::user()->provider_id)->orderBy("created_at","asc")->first();
        if($year){
            $year_to_months = (date("Y")-date("Y",strtotime($year->completion_date)));
            $months = date("n")-date("n",strtotime($year->completion_date));
            $total_months = abs($year_to_months + $months);
      
            $set_of_months = array();
            $start_month = date("n",strtotime($year->completion_date));
            $start_year = date("Y",strtotime($year->completion_date));
           
            for($x = 0; $x < $total_months; $x++){
                if($start_month <= 11){
                    array_push($set_of_months,[
                        "value" => $start_month."-".$start_year,
                        "display" => $monthNames[$start_month-1]." ".$start_year
                    ]);
                    $start_month++;
                }else{
                    array_push($set_of_months,[
                        "value" => $start_month."-".$start_year,
                        "display" => $monthNames[$start_month-1]." ".$start_year
                    ]);
                    $start_month=1;
                    
                    $start_year = date("Y",strtotime("+1 years",strtotime($year->completion_date)));
                    
                }
            
            }
            return $set_of_months;
        }else{
            return [];
        }
    }

    function webinarCompletionInsert($webinar_id){
        // $webinar_session = Webinar_Session::where("webinar_id",$webinar_id)->o

        // $participants = Purchase_Item::select(DB::raw("count(id) as participant_count"))
        //                                         ->where("payment_status","paid")
        //                                         ->where("fast_status","complete")
        //                                         ->where("data_id",$webinar_id)
        //                                         ->where("type","webinar")
        //                                         //->whereMonth("updated_at", '=', Carbon::now()->subMonth()->format("m"))
        //                                         //->whereYear("updated_at", '=', Carbon::now()->subMonth()->format("Y"))
        //                                         ->whereMonth("updated_at", '=', Carbon::now()->format("m"))
        //                                         ->whereYear("updated_at", '=', Carbon::now()->format("Y"))
        //                                         ->first();
                                                     
        // CompletionReport::insert([
        //     "provider_id" => _current_provider()->id,
        //     "type" => "webinar",
        //     "data_id" => $webinar_id,
        //     "participants" => $participants->participant_count,
        //     "completion_date" => date("Y-m-d H:i:s",strtotime("-1 months")),
        //     "created_at" => date('Y-m-d H:i:s'),
        //     "updated_at" => date('Y-m-d H:i:s'),
        // ]);
    }
}

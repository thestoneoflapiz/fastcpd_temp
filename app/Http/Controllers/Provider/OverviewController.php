<?php

namespace App\Http\Controllers\Provider;

use App\{
        User, Provider, Logs, Co_Provider, Provider_Permission,Course,
        CoursePerformance,Instructor,
        Payout, Purchase, Purchase_Item, Review, Course_Progress, Course_Rating,
        Certificate,
        Webinar,
        Webinar_Progress, Webinar_Rating, Webinar_Attendance, Webinar_Performance,
    };
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Response;
use Session;

class OverviewController extends Controller
{
    function enrollment_list(Request $request) : JsonResponse
    {   
    
        $provider_id = _current_provider();
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '12';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        
        $get_enrolled = Purchase_Item::select(
                            "courses.id","webinars.id","webinars.title as webinar","purchase_items.type",
                            "purchase_items.id as purchase_item_id","purchase_items.created_at as purchase_date",
                            "purchase_items.purchase_id as purchase_id",
                            "courses.title as course",
                            "purchase_items.provider_revenue as provider_revenue",
                            "purchase_items.data_id as data_id",
                            "purchases.updated_at as payment_date","purchases.payment_status as payment_status"
                        )
                        ->whereMonth("purchases.updated_at",date("m"))
                        ->whereYear("purchases.updated_at",date("Y"))
                        ->where("purchases.payment_status","paid")
                       // ->where("purchase_items.fast_status","complete")
                        ->where("providers.id",$provider_id->id)
                        ->leftJoin("courses","courses.id","purchase_items.data_id")
                        ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                        ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                        ->leftJoin("providers",function($join){
                            $join->on("providers.id","webinars.provider_id");
                            $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                            $join->orOn("providers.id","courses.provider_id");
                            $join->on("purchase_items.type","=",DB::raw("'course'"));
                        })->groupBy("courses.id","webinars.id");
        $quotient = floor($get_enrolled->count() / $perPage);
        $reminder = ($get_enrolled->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $get_enrolled->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "course",
        );
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["course","type", "provider_revenue", "total_enrollment"]) ? $paramTable["sort"]["field"] : "course";
          
            $get_enrolled = $get_enrolled->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                            ->skip($offset)->take($paramTable["pagination"]["perpage"]);
                  
        }else{
            if($perPage){
                $get_enrolled = $get_enrolled->skip($offset)->take($perPage);
            }else{
                $get_enrolled = $get_enrolled->skip($offset)->take(10);
            }
        }
      
        $get_enrolled = $get_enrolled->get();
     // dd($get_enrolled);
        $data= array_map(function($enrolled) use($request){
        $count_total_enrolled = Purchase_Item::where("purchase_items.data_id",$enrolled['data_id'])
                                            ->whereMonth("purchases.updated_at",date("m"))
                                            ->whereYear("purchases.updated_at",date("Y"))
                                            ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                                            ->count();
        $current_course_rev = Purchase_Item::select(DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"))
                                            ->where(["purchase_items.type"=>$enrolled['type'],"purchase_items.data_id"=>$enrolled['data_id']])
                                            ->whereMonth("purchases.updated_at",date("m"))
                                            ->whereYear("purchases.updated_at",date("Y"))
                                            ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                                            ->first();
        $title = $enrolled['type'] == "course" ? $enrolled['course'] : $enrolled['webinar'];
        return[
            "id" => $enrolled['purchase_item_id'],
            "purchase_date" => date("M. d 'y", strtotime($enrolled['purchase_date'])),  
            "course" => $title,
            "type" => $enrolled['type'] == "course" ? "Video on Demand" : "Webinar",
            "revenue" =>  $current_course_rev->provider_revenue,
            "total_enrolled" => $count_total_enrolled
        ];
        },$get_enrolled->toArray());

        return response()->json(["data"=>$data,"meta" => $meta, "total"=>count($data)], 200);
    }

    function rating_list(Request $request) : JsonResponse
    {  
        $provider_id = _current_provider();
        $report = $request->month;
        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        

        $get_reviews = Course_Rating::select("course_ratings.id as review_id",
                                        "course_ratings.created_at as feedback_date",
                                        "courses.title as course",
                                        "users.name as customer",
                                        "course_ratings.rating as rating",
                                        "course_ratings.remarks as feedback"
                                    )->where("courses.provider_id",$provider_id->id)
                                    ->whereMonth("course_ratings.created_at",date("m"))
                                    ->whereYear("course_ratings.created_at",date("Y"))
                                    ->leftJoin("courses","courses.id","course_ratings.course_id")
                                    ->leftJoin("users","users.id","course_ratings.user_id");

        $quotient = floor($get_reviews->count() / $perPage);
        $reminder = ($get_reviews->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $get_reviews->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "purchase_date",
        );
       
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["feedback_date", "customer", "course","rating"]) ? $paramTable["sort"]["field"] : "feedback_date";
            $get_reviews = $get_reviews->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                            ->skip($offset)->take($paramTable["pagination"]["perpage"]);
                           
        }else{
            if($perPage){
                $get_reviews = $get_reviews->skip($offset)->take($perPage);
            }else{
                $get_reviews = $get_reviews->skip($offset)->take(10);
            }
        }
        $get_reviews = $get_reviews->get();
        $data = array_map(function($review) use ($request){
            return [
                "id" => $review["review_id"],
                "feedback_date" => date("M. d 'y", strtotime($review["feedback_date"])),
                "customer" => $review["customer"],
                "course" => $review["course"],
                "rating" => $review["rating"],
                "feedback" => $review["feedback"]
            ];
        },$get_reviews->toArray());
                            

    
        return response()->json(["data"=>$data, "meta"=> $meta, "total"=>count($data)], 200);
    }
    
    function currentMonthList(Request $request):JsonResponse{
        $provider_id = _current_provider();
      
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '12';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;

        $get_purchases = Purchase_Item::select(
                        "purchase_items.type as type","webinars.title as webinar",
                        "purchase_items.id as purchase_item_id","purchase_items.created_at as purchase_date",
                        "purchase_items.purchase_id as purchase_id",
                        "purchases.updated_at as payment_date","users.name as customer",
                        "courses.title as course","purchase_items.voucher as coupon_code","courses.price as original_price",
                        "purchase_items.channel as channel","purchase_items.total_amount as price_paid",
                        "purchase_items.discount as discount","purchase_items.fast_revenue as fast_revenue",
                        "purchase_items.provider_revenue as provider_revenue"
                    )
                    ->whereMonth("purchases.updated_at",date("m"))
                    ->whereYear("purchases.updated_at",date("Y"))
                    ->where("purchases.payment_status","paid")
                    //->where("purchase_items.fast_status","complete")
                    ->where("providers.id", $provider_id->id)
                    ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                    ->leftJoin("courses","courses.id","purchase_items.data_id")
                    ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                    ->leftJoin("providers",function($join){
                        $join->on("providers.id","webinars.provider_id");
                        $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                        $join->orOn("providers.id","courses.provider_id");
                        $join->on("purchase_items.type","=",DB::raw("'course'"));
                    })
                    ->leftJoin("users","users.id","purchase_items.user_id");
                    $quotient = floor($get_purchases->count() / $perPage);
                    $reminder = ($get_purchases->count() % $perPage);
                    $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;
            
                    $meta = array(
                        "page"=> $paramTable["pagination"]["page"] ?? '1',
                        "pages"=>  $pagesScold,
                        "perpage"=> $perPage,
                        "total"=> $get_purchases->count(),
                        "sort"=> $paramTable["sort"]["sort"] ?? "asc",
                        "field"=> $paramTable["sort"]["field"] ?? "purchase_date",
                    );
                    if(array_key_exists("sort", $paramTable)){
                        $retouch_sort = in_array($paramTable["sort"]["field"], ["purchase_date", "customer", "course", "coupon_code","channel","price_paid"]) ? $paramTable["sort"]["field"] : "purchase_date";
                      
                        $get_purchases = $get_purchases->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                                        ->skip($offset)->take($paramTable["pagination"]["perpage"]);
                              
                    }else{
                        if($perPage){
                            $get_purchases = $get_purchases->skip($offset)->take($perPage);
                        }else{
                            $get_purchases = $get_purchases->skip($offset)->take(10);
                        }
                    }
        $get_purchases = $get_purchases->get();
        $data= array_map(function($purchase) use($request){
            $provider_id = _current_provider();
            $original_price = $purchase["original_price"];
            if($purchase['coupon_code'] == null && $purchase["discount"] == null ){
                $channel = "";
            }else if($purchase['channel'] == "fast_promo"){
                $channel = "Fast Promo";
            }else if($purchase['channel'] == "provider_promo"){
                $channel = "Your Promo";
            }else if($purchase['channel'] == "promoter_promo"){
                $channel = "Promoter Promo";
            }else{
                $channel = "Refund";
            }
            $percentage = _get_revenue_percent($provider_id->id,$purchase['channel'], $purchase["type"]);
            $title = $purchase['type'] == "course" ? $purchase['course'] : $purchase['webinar'] ;
            return[
                "id" => $purchase['purchase_item_id'],
                "purchase_date" => date("M. d 'y", strtotime($purchase['purchase_date'])),
                "payment_date" => date("M. d 'y", strtotime($purchase['payment_date'])),
                "type" => $purchase['type'] == "course" ? "Video on Demand" : "Webinar",
                "customer" => $purchase['customer'],
                "course" => $title,
                "coupon_code" => $purchase['coupon_code'],
                "channel" => $channel,
                "price_paid" =>  $purchase['price_paid'],
                "original_price" => $purchase['original_price'],
                "revenue" => $purchase['provider_revenue'],
                "percent" => Auth::user()->superadmin == "none" ? $percentage["provider"] : $percentage["fast"],
            ];
        },$get_purchases->toArray());
        // $total_revenue = Payout::select(DB::raw('sum(provider_revenue) as total_revenue'))
        //                         ->where("provider_id",$provider_id->id)->first();

        $current_month_overview = Purchase_Item::select(
                                        DB::raw('sum(purchase_items.provider_revenue) as current_revenue'),
                                        DB::raw('count(purchase_items.id) as current_enrolled')
                                    )->whereMonth("purchases.updated_at",date("m"))
                                    ->whereYear("purchases.updated_at",date("Y"))
                                    ->where("providers.id",$provider_id->id)
                                    ->where("purchases.payment_status","paid")
                                    //->where("purchase_items.fast_status","complete")
                                    ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                                    ->leftJoin("courses","courses.id","purchase_items.data_id")
                                    ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                                    ->leftJoin("providers",function($join){
                                        $join->on("providers.id","webinars.provider_id");
                                        $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                                        $join->orOn("providers.id","courses.provider_id");
                                        $join->on("purchase_items.type","=",DB::raw("'course'"));
                                    })
                                    ->leftJoin("users","users.id","purchase_items.user_id")->first();
        $total_enrolled = Purchase_Item::select(
                                        DB::raw('count(purchase_items.id) as total_enrolled')
                                    )
                                    ->where("providers.id",$provider_id->id)
                                    ->where("purchase_items.payment_status","paid")
                                    //->where("purchase_items.fast_status","complete")
                                    ->leftJoin("courses","courses.id","purchase_items.data_id")
                                    ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                                    ->leftJoin("providers",function($join){
                                        $join->on("providers.id","webinars.provider_id");
                                        $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                                        $join->orOn("providers.id","courses.provider_id");
                                        $join->on("purchase_items.type","=",DB::raw("'course'"));
                                    })
                                    ->leftJoin("users","users.id","purchase_items.user_id")->first();
        $ratings_course = Course::select(
                                    DB::raw('count(course_ratings.id) as rating_count'),
                                    DB::raw('sum(course_ratings.rating) as rating_sum')
                                )
                                ->where("providers.id",$provider_id->id)
                                ->leftJoin("providers","providers.id","courses.provider_id")
                                ->leftJoin("course_ratings","course_ratings.course_id","courses.id")
                                ->first();
        $current_rating_course = Course::select(
                                    DB::raw('count(course_ratings.id) as rating_count')
                                )
                                ->where("providers.id",$provider_id->id)
                                ->whereMonth("course_ratings.created_at",date("m"))
                                ->whereYear("course_ratings.created_at",date("Y"))
                                ->leftJoin("providers","providers.id","courses.provider_id")
                                ->leftJoin("course_ratings","course_ratings.course_id","courses.id")
                                ->first();
                               
        $current_rating_webinar = Webinar::select(
                                    DB::raw('count(webinar_ratings.id) as rating_count')
                                )
                                ->where("providers.id",$provider_id->id)
                                ->whereMonth("webinar_ratings.created_at",date("m"))
                                ->whereYear("webinar_ratings.created_at",date("Y"))
                                ->leftJoin("providers","providers.id","webinars.provider_id")
                                ->leftJoin("webinar_ratings","webinar_ratings.webinar_id","webinars.id")
                                ->first();
        $ratings_webinar = Webinar::select(
                                    DB::raw('count(webinar_ratings.id) as rating_count'),
                                    DB::raw('sum(webinar_ratings.rating) as rating_sum')
                                )
                                ->where("providers.id",$provider_id->id)
                                ->leftJoin("providers","providers.id","webinars.provider_id")
                                ->leftJoin("webinar_ratings","webinar_ratings.webinar_id","webinars.id")
                                ->first();
                               
       
                          
        $total_ratings_course = $ratings_course->rating_sum ? ($ratings_course->rating_sum / $ratings_course->rating_count) : 0;
        $total_ratings_webinar = $ratings_webinar->rating_sum ? ($ratings_webinar->rating_sum / $ratings_webinar->rating_count) : 0;

        $total_revenue = Purchase_Item::select(
            DB::raw('sum(purchase_items.provider_revenue) as total_revenue')
        )
        ->where("providers.id",$provider_id->id)
        ->where("purchase_items.payment_status","paid")
        //->where("purchase_items.fast_status","complete")
        ->leftJoin("courses","courses.id","purchase_items.data_id")
        ->leftJoin("webinars","webinars.id","purchase_items.data_id")
        ->leftJoin("providers",function($join){
            $join->on("providers.id","webinars.provider_id");
            $join->on("purchase_items.type","=",DB::raw("'webinar'"));
            $join->orOn("providers.id","courses.provider_id");
            $join->on("purchase_items.type","=",DB::raw("'course'"));
        })
        ->leftJoin("users","users.id","purchase_items.user_id")->first();

        return response()->json([
                            "data"=>$data, 
                            "total"=>count($data), 
                            "total_revenue" => number_format($total_revenue->total_revenue ,2), 
                            "total_enrolled" => $total_enrolled->total_enrolled, 
                            "total_ratings_course" => number_format($total_ratings_course,1),
                            "total_ratings_webinar" => number_format($total_ratings_webinar,1), 
                            "current_month_rev" => number_format($current_month_overview->current_revenue,2),
                            "current_month_enrolled" => $current_month_overview->current_enrolled,
                            "current_month_ratings_course" => $current_rating_course->rating_count,
                            "current_month_ratings_webinar" => $current_rating_webinar->rating_count,
                            "meta" => $meta
                        ], 
                    200);
    }
    
    function showReviews(Request $request):JsonResponse{
        $provider_id = _current_provider();
        $course_id = $request->course_id;
        $counter = $request->counter;
        $filter = $request->filter;
        $type = $request->type;

       if($type == "course"){
            $reviews = Course_Rating::select("course_ratings.id as review_id",
                                        "course_ratings.created_at as feedback_date",
                                        "courses.title as course",
                                        "users.name as customer",
                                        "course_ratings.rating as rating",
                                        "course_ratings.remarks as feedback",
                                        "users.image as image"
                                    )->where("courses.provider_id",$provider_id->id)
                                    ->where("courses.fast_cpd_status","live")
                                    ->where("course_ratings.course_id",$course_id)
                                    ->leftJoin("courses","courses.id","course_ratings.course_id")
                                    ->leftJoin("users","users.id","course_ratings.user_id")->orderBy("course_ratings.created_at","desc")->take($counter);
            $reviews_stats = Course_Rating::select(
                                        DB::raw("count(course_ratings.id) as reviews_count"),
                                        DB::raw("sum(course_ratings.rating) as total_ratings")
                                    )->where("courses.provider_id",$provider_id->id)
                                    ->where("courses.fast_cpd_status","live")
                                    ->where("course_ratings.course_id",$course_id)
                                    ->leftJoin("courses","courses.id","course_ratings.course_id")
                                    ->leftJoin("users","users.id","course_ratings.user_id")->first();
            $total_ratings = $reviews_stats ? ($reviews_stats->reviews_count != 0 ? $reviews_stats->total_ratings / $reviews_stats->reviews_count : 0) : 0;
        
            $course_details = Course::where("id",$course_id)->first();
            if($course_details->instructor_id){
                $instructors = Instructor::select("users.name as fullname")
                                ->whereIn("users.id",json_decode($course_details->instructor_id))
                                ->leftJoin("users","instructors.user_id","users.id")
                                ->groupBy("users.id")
                                ->get();
            }else{
                $instructors = null;
            }
    
            $performance_course = $this->course_performance_stats($course_id);

       }else{
            $reviews = Webinar_Rating::select("webinar_ratings.id as review_id",
                                        "webinar_ratings.created_at as feedback_date",
                                        "webinars.title as course",
                                        "users.name as customer",
                                        "webinar_ratings.rating as rating",
                                        "webinar_ratings.remarks as feedback",
                                        "users.image as image"
                                    )->where("webinars.provider_id",$provider_id->id)
                                    ->where("webinars.fast_cpd_status","live")
                                    ->where("webinar_ratings.webinar_id",$course_id)
                                    ->leftJoin("webinars","webinars.id","webinar_ratings.webinar_id")
                                    ->leftJoin("users","users.id","webinar_ratings.user_id")->orderBy("webinar_ratings.created_at","desc")->take($counter);
            $reviews_stats = Webinar_Rating::select(
                                        DB::raw("count(webinar_ratings.id) as reviews_count"),
                                        DB::raw("sum(webinar_ratings.rating) as total_ratings")
                                    )->where("webinars.provider_id",$provider_id->id)
                                    ->where("webinars.fast_cpd_status","live")
                                    ->where("webinar_ratings.webinar_id",$course_id)
                                    ->leftJoin("webinars","webinars.id","webinar_ratings.webinar_id")
                                    ->leftJoin("users","users.id","webinar_ratings.user_id")->first();
            $total_ratings = $reviews_stats ? ($reviews_stats->reviews_count != 0 ? $reviews_stats->total_ratings / $reviews_stats->reviews_count : 0) : 0;
        
            $course_details = Webinar::where("id",$course_id)->first();
            if($course_details->instructor_id){
                $instructors = Instructor::select("users.name as fullname")
                                ->whereIn("users.id",json_decode($course_details->instructor_id))
                                ->leftJoin("users","instructors.user_id","users.id")
                                ->groupBy("users.id")
                                ->get();
                                
            }else{
                $instructors = null;
            }
            $performance_course = $this->webinar_performance_stats($course_id);
       }
       
                     
        if($filter != null){
            if($filter == 1){
                $reviews->where("remarks","!=","");
            }else{
                $reviews->where("remarks",null);
            }
            
        }
        
      

        // $data = array_map(function($enrolled) use($request){

        // },$reviews->toArray());
        
        $reviews = $reviews->get();
        //date_default_timezone_set('Asia/Manila');
        if($reviews){
            $data = array_map(function($review) use($request){

                return[
                    "review_id" => $review['review_id'],
                    "feedback_date" => \Carbon\Carbon::parse($review['feedback_date'])->diffForHumans(),
                    "course" => $review['course'],
                    "customer" => $review['customer'],
                    "rating" => $review['rating'],
                    "feedback" => $review['feedback'],
                    "image" => $review['image']
                ];

            },$reviews->toArray());
        }else{
            $data=null;
        }
            
        return response()->json([
                        "data" => $data, "counter" => $counter, 
                        "course_details" => $course_details,
                        "instructors" => $instructors,
                        "total_ratings" => number_format($total_ratings,1),
                        "performance_course" => $performance_course
                    ],200);
    }
    
    function checkProgressReview(Request $request):JsonResponse{
        $counter_error = 0;
        if($request->has("course_id")){
            $course_id = $request->course_id;
            
            $course_progress = Course_Progress::where("course_id",$course_id)->where("user_id",Auth::user()->id)->get();
            $course_ratings = Course_Rating::where("course_id",$course_id)->where("user_id",Auth::user()->id)->first();
            $course_performance = CoursePerformance::where("course_id",$course_id)->where("user_id",Auth::user()->id)->first();

            if($course_performance){
                $rating_step = 3;
            }else if($course_ratings){
                $rating_step = 2;
            }else{
                $rating_step = 1;
            }
            
            if(count($course_progress) != 0){
                foreach($course_progress as $progress){
                    switch($progress->type){
                        case "video":
                            $counter_error = $progress->status != "completed" ? $counter_error+1 : $counter_error;
                        
                        break;
                        case "quiz":
                            $counter_error = ($progress->status == "passed" || $progress->status == "failed") ? $counter_error : $counter_error+1;
                        break;
                        case "article";
                            $counter_error = $progress->status != "completed" ? $counter_error+1 : $counter_error;
                        break;
                        default: 
                            $counter_error++;
                        break;
                    }
                }
            }else{
                return response()->json(["status" => "in-progress","rating_step" => $rating_step],200);
            }
        }else{
            $webinar_id = $request->webinar_id;
            
            $webinar_progress = Webinar_Progress::where("webinar_id",$webinar_id)->where("user_id",Auth::user()->id)->get();
            $webinar_ratings = Webinar_Rating::where("webinar_id",$webinar_id)->where("user_id",Auth::user()->id)->first();
            $webinar_performance = Webinar_Performance::where("webinar_id",$webinar_id)->where("user_id",Auth::user()->id)->first();

            if($webinar_performance){
                $rating_step = 3;
            }else if($webinar_ratings){
                $rating_step = 2;
            }else{
                $rating_step = 1;
            }
            
            if(count($webinar_progress) != 0){
                foreach($webinar_progress as $progress){
                    switch($progress->type){
                        case "video":
                            $counter_error = $progress->status != "completed" ? $counter_error+1 : $counter_error;
                        
                        break;
                        case "quiz":
                            $counter_error = ($progress->status == "passed" || $progress->status == "failed") ? $counter_error : $counter_error+1;
                        break;
                        case "article";
                            $counter_error = $progress->status != "completed" ? $counter_error+1 : $counter_error;
                        break;
                        default: 
                            $counter_error++;
                        break;
                    }
                }
            }else{
                return response()->json(["status" => "in-progress","rating_step" => $rating_step],200);
            }
        }

        if($counter_error != 0){
            return response()->json(["status" => "in-progress","rating_step" => $rating_step],200);
        }

        return response()->json(["status" => "finished","rating_step" => $rating_step],200);
    }
    
    function savedRatingsRemarks(Request $request):JsonResponse{
        $remarks = $request->remarks;
        $star_rating = $request->ratings;

        if($request->has("course_id")){
            $course_id = $request->course_id;
            $ratings = Course_Rating::insert([
                "user_id" => Auth::user()->id,
                "course_id" => $course_id,
                "rating" => $star_rating,
                "remarks" => $remarks,
                "created_at" =>  date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
        }else{
            $webinar_id = $request->webinar_id;
            $ratings = Webinar_Rating::insert([
                "user_id" => Auth::user()->id,
                "webinar_id" => $webinar_id,
                "rating" => $star_rating,
                "remarks" => $remarks,
                "created_at" =>  date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
        }
            
        if($ratings){
            return response()->json(["message"=>"Rating Submitted."],200);
        }else{
            return response()->json(["message"=>"Something went wrong! Please contact your administrator."],500);
        }
    }

    function savedPerformance(Request $request):JsonResponse{
        $valuable_information = $request->valuable_information;
        $concepts_clear = $request->concepts_clear;
        $instructor_delivery = $request->instructor_delivery;
        $opportunities = $request->opportunities;
        $expectations = $request->expectations;
        $knowledgeable = $request->knowledgeable;

        if($request->has("course_id")){
            $course_id = $request->course_id;
            $performance = new CoursePerformance;
            $performance->course_id = $course_id;
        }else{
            $webinar_id = $request->webinar_id;
            $performance = new Webinar_Performance;
            $performance->webinar_id = $webinar_id;
        }
            $performance->valuable_information = $valuable_information;
            $performance->concepts_clear = $concepts_clear;
            $performance->instructor_delivery = $instructor_delivery;
            $performance->opportunities = $opportunities;
            $performance->expectations = $expectations;
            $performance->knowledgeable = $knowledgeable;
            $performance->user_id = Auth::user()->id;
            $performance->created_at =  date("Y-m-d H:i:s");
            $performance->updated_at = date("Y-m-d H:i:s");

        if($performance->save()){
            if($request->has("course_id")){
                Course_Rating::where(["user_id" => Auth::user()->id,"course_id" => $course_id])->update([
                    "optional_remarks" =>json_encode($performance->id)
                ]);
            }else{
                Webinar_Rating::where(["user_id" => Auth::user()->id,"webinar_id" => $webinar_id])->update([
                    "optional_remarks" =>json_encode($performance->id)
                ]);
            }
            
            if($request->has("course_id")){
                $hash_code = Crypt::encryptString(Auth::user()->id."-course-{$course_id}-".date("H:i:s"));
            }else{
                $hash_code = Crypt::encryptString(Auth::user()->id."-webinar-{$webinar_id}-".date("H:i:s"));
            }
            $hash_code = mb_substr($hash_code,0,15);
          
            $certificate = new Certificate;
            $certificate->user_id = Auth::user()->id;
            $certificate->type = $request->has("course_id") ? "course" : "webinar";
            $certificate->data_id = $request->has("course_id") ? $course_id : $webinar_id;
            $certificate->url = "/data/pdf/certificate?certificate_code=".$hash_code;
            $certificate->qr_link = "https://api.qrserver.com/v1/create-qr-code/?data=".\config('app.link')."/verify/{$hash_code}?size=1000x1000";
            $certificate->certificate_code = $hash_code;
            $certificate->created_at = date("Y-m-d H:i:s");
            $certificate->updated_at =  date("Y-m-d H:i:s");
            
            if($certificate->save()){
                if($request->has("course_id")){
                    _send_notification_email(Auth::user()->email,"generate_certificate", $course_id, $certificate->id);
                }
                return response()->json(["message" => "success","certificate_code" => $hash_code],200);
            }
            return response()->json(["message" => "error","certificate_code" => $hash_code],500);
        }else{
            return response()->json(["message" => "error"],500);
        }  
    }
    function webinar_rating_list(Request $request) : JsonResponse
    {  
        $provider_id = _current_provider();
        $report = $request->month;
        $pagination = $request->pagination;
        $sort = $request->sort;
        $paramTable = $request->all();
        $page = $paramTable["pagination"]["page"];
        $perPage = $paramTable["pagination"]["perpage"] ?? '10';
        $offset = $page == 1 ? 0 : ($page - 1) * $perPage;
        

        $get_reviews = Webinar_Rating::select("webinar_ratings.id as review_id",
                                        "webinar_ratings.created_at as feedback_date",
                                        "webinars.title as webinar",
                                        "users.name as customer",
                                        "webinar_ratings.rating as rating",
                                        "webinar_ratings.remarks as feedback"
                                    )->where("webinars.provider_id",$provider_id->id)
                                    ->whereMonth("webinar_ratings.created_at",date("m"))
                                    ->whereYear("webinar_ratings.created_at",date("Y"))
                                    ->leftJoin("webinars","webinars.id","webinar_ratings.webinar_id")
                                    ->leftJoin("users","users.id","webinar_ratings.user_id");

        $quotient = floor($get_reviews->count() / $perPage);
        $reminder = ($get_reviews->count() % $perPage);
        $pagesScold = $reminder > 0 ? $quotient+1 : $quotient;

        $meta = array(
            "page"=> $paramTable["pagination"]["page"] ?? '1',
            "pages"=>  $pagesScold,
            "perpage"=> $perPage,
            "total"=> $get_reviews->count(),
            "sort"=> $paramTable["sort"]["sort"] ?? "asc",
            "field"=> $paramTable["sort"]["field"] ?? "purchase_date",
        );
       
        if(array_key_exists("sort", $paramTable)){
            $retouch_sort = in_array($paramTable["sort"]["field"], ["feedback_date", "customer", "webinar","rating"]) ? $paramTable["sort"]["field"] : "feedback_date";
            $get_reviews = $get_reviews->orderBy($retouch_sort, $paramTable["sort"]["sort"])
                            ->skip($offset)->take($paramTable["pagination"]["perpage"]);
                           
        }else{
            if($perPage){
                $get_reviews = $get_reviews->skip($offset)->take($perPage);
            }else{
                $get_reviews = $get_reviews->skip($offset)->take(10);
            }
        }
        $get_reviews = $get_reviews->get();
        $data = array_map(function($review) use ($request){
            return [
                "id" => $review["review_id"],
                "feedback_date" => date("M. d 'y", strtotime($review["feedback_date"])),
                "customer" => $review["customer"],
                "webinar" => $review["webinar"],
                "rating" => $review["rating"],
                "feedback" => $review["feedback"]
            ];
        },$get_reviews->toArray());
                            

    
        return response()->json(["data"=>$data, "meta"=> $meta, "total"=>count($data)], 200);
    }

    function course_performance_stats($course_id){
        $performance_count = CoursePerformance::select(
            DB::raw("count(id) as performance_count")
        )->where("course_id",$course_id)->first();

        $valuable_info = CoursePerformance::select(
            DB::raw("count(id) as valuable_info_count")
        )->where(["course_id" => $course_id, "valuable_information" => "yes"])->first();

        $concepts = CoursePerformance::select(
            DB::raw("count(id) as concepts_clear_count")
        )->where(["course_id" => $course_id, "concepts_clear" => "yes"])->first();

        $instructor_delivery = CoursePerformance::select(
            DB::raw("count(id) as instructor_delivery_count")
        )->where(["course_id" => $course_id, "instructor_delivery" => "yes"])->first();

        $opportunities = CoursePerformance::select(
            DB::raw("count(id) as opportunities_count")
        )->where(["course_id" => $course_id, "opportunities" => "yes"])->first();

        $expectations = CoursePerformance::select(
            DB::raw("count(id) as expectations_count")
        )->where(["course_id" => $course_id, "expectations" => "yes"])->first();

        $knowledgeable = CoursePerformance::select(
            DB::raw("count(id) as knowledgeable_count")
        )->where(["course_id" => $course_id, "knowledgeable" => "yes"])->first();
        if($performance_count->performance_count != 0){
            $valuable_info = ( $valuable_info->valuable_info_count / $performance_count->performance_count) * 100;
            $concepts = ( $concepts->concepts_clear_count / $performance_count->performance_count) * 100;
            $instructor_delivery = ( $instructor_delivery->instructor_delivery_count / $performance_count->performance_count) * 100;
            $opportunities = ( $opportunities->opportunities_count / $performance_count->performance_count) * 100;
            $expectations = ( $expectations->expectations_count / $performance_count->performance_count) * 100;
            $knowledgeable = ( $knowledgeable->knowledgeable_count / $performance_count->performance_count) * 100;
        }else{
            $valuable_info = 0;
            $concepts = 0;
            $instructor_delivery = 0;
            $opportunities = 0;
            $expectations = 0;
            $knowledgeable = 0;
        }
        

        return [
           "performance_count" => $performance_count->performance_count,
           "valuable_info" => $valuable_info,
           "concepts" => $concepts,
           "instructor_delivery" => $instructor_delivery,
           "opportunities" => $opportunities,
           "expectations" => $expectations,
           "knowledgeable" => $knowledgeable
        ];
    }

    function webinar_performance_stats($course_id){
        $performance_count = Webinar_Performance::select(
            DB::raw("count(id) as performance_count")
        )->where("webinar_id",$course_id)->first();

        $valuable_info = Webinar_Performance::select(
            DB::raw("count(id) as valuable_info_count")
        )->where(["webinar_id" => $course_id, "valuable_information" => "yes"])->first();

        $concepts = Webinar_Performance::select(
            DB::raw("count(id) as concepts_clear_count")
        )->where(["webinar_id" => $course_id, "concepts_clear" => "yes"])->first();

        $instructor_delivery = Webinar_Performance::select(
            DB::raw("count(id) as instructor_delivery_count")
        )->where(["webinar_id" => $course_id, "instructor_delivery" => "yes"])->first();

        $opportunities = Webinar_Performance::select(
            DB::raw("count(id) as opportunities_count")
        )->where(["webinar_id" => $course_id, "opportunities" => "yes"])->first();

        $expectations = Webinar_Performance::select(
            DB::raw("count(id) as expectations_count")
        )->where(["webinar_id" => $course_id, "expectations" => "yes"])->first();

        $knowledgeable = Webinar_Performance::select(
            DB::raw("count(id) as knowledgeable_count")
        )->where(["webinar_id" => $course_id, "knowledgeable" => "yes"])->first();
        if($performance_count->performance_count != 0){
            $valuable_info = ( $valuable_info->valuable_info_count / $performance_count->performance_count) * 100;
            $concepts = ( $concepts->concepts_clear_count / $performance_count->performance_count) * 100;
            $instructor_delivery = ( $instructor_delivery->instructor_delivery_count / $performance_count->performance_count) * 100;
            $opportunities = ( $opportunities->opportunities_count / $performance_count->performance_count) * 100;
            $expectations = ( $expectations->expectations_count / $performance_count->performance_count) * 100;
            $knowledgeable = ( $knowledgeable->knowledgeable_count / $performance_count->performance_count) * 100;
        }else{
            $valuable_info = 0;
            $concepts = 0;
            $instructor_delivery = 0;
            $opportunities = 0;
            $expectations = 0;
            $knowledgeable = 0;
        }
        

        return [
           "performance_count" => $performance_count->performance_count,
           "valuable_info" => $valuable_info,
           "concepts" => $concepts,
           "instructor_delivery" => $instructor_delivery,
           "opportunities" => $opportunities,
           "expectations" => $expectations,
           "knowledgeable" => $knowledgeable
        ];
    }

}

<?php

use App\{
    User, Logs, Info, 
    Profession, Top_Profession,
    Provider, Co_Provider, Instructor, 
    Course, Profile_Requests, Review, 
    Provider_Permission,
    Section, Article, Video, Quiz,
    Quiz_Item, Quiz_Score,
    Course_Progress,
    Instructor_Permissions, Handout,
    Instructor_Resume,
    Course_Rating, CoursePerformance, System_Rating_Report, 
    
    My_Cart, Voucher, Notification, Promoter,
    Purchase, Purchase_Item,
    Image_Intervention, Payout,

    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Instructor_Permission, Webinar_Progress,
    Webinar_Performance, Webinar_Rating, Webinar_Attendance, 
};
use Illuminate\Support\Facades\{Auth};

if(! function_exists('_get_promoter_voucher')){
    function _get_promoter_voucher($id){
        
        $voucher = Voucher::where("channel", "promoter_promo")
            ->whereJsonContains('data_id', $id )
            ->first();

        return $voucher;
    }
}
if(! function_exists('_get_balance')){
    function _get_balance($id){
        $voucher = _get_promoter_voucher($id);
        $existing_payouts = Payout::where("data_id",$id)
                            ->where("type","promoter")
                            ->latest('created_at')
                            ->first();
        if($existing_payouts){
            $course_revenues = Course::select(
                DB::raw("sum(purchase_items.promoter_revenue) as promoter_revenue"),
                DB::raw("sum(purchase_items.price) as price"),
                DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"))
            ->where("purchase_items.payment_status","paid")
            ->where("purchases.updated_at",">",$existing_payouts->created_at)
            ->where("purchase_items.voucher",$voucher->voucher_code)
            ->where("purchase_items.type","course")
            ->leftJoin("purchase_items","courses.id","purchase_items.data_id")
            ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
            ->get();

            $webinar_revenues = Webinar::select(
                DB::raw("sum(purchase_items.promoter_revenue) as promoter_revenue"),
                DB::raw("sum(purchase_items.price) as price"),
                DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"))
            ->where("purchase_items.payment_status","paid")
            ->where("purchases.updated_at",">",$existing_payouts->created_at)
            ->where("purchase_items.type","webinar")
            ->where("purchase_items.voucher",$voucher->voucher_code)
            ->leftJoin("purchase_items","webinars.id","purchase_items.data_id")
            ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
            ->get();

        }else{
            $course_revenues = Course::select(
                DB::raw("sum(purchase_items.promoter_revenue) as promoter_revenue"),
                DB::raw("sum(purchase_items.price) as price"),
                DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"))
            ->where("purchase_items.payment_status","paid")
            ->where("purchase_items.voucher",$voucher->voucher_code)
            ->where("purchase_items.type","course")
            ->leftJoin("purchase_items","courses.id","purchase_items.data_id")
            ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
            ->get();

            $webinar_revenues = Webinar::select(
                DB::raw("sum(purchase_items.promoter_revenue) as promoter_revenue"),
                DB::raw("sum(purchase_items.price) as price"),
                DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"))
            ->where("purchase_items.payment_status","paid")
            ->where("purchase_items.type","webinar")
            ->where("purchase_items.voucher",$voucher->voucher_code)
            ->leftJoin("purchase_items","webinars.id","purchase_items.data_id")
            ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
            ->get();
        }   
        
        $data = array(
            "balance" => $course_revenues[0]->promoter_revenue + $webinar_revenues[0]->promoter_revenue,
            "collected" => $course_revenues[0]->price + $webinar_revenues[0]->price,
            "provider_revenue" => $course_revenues[0]->provider_revenue + $webinar_revenues[0]->provider_revenue,
            "fast_revenue" => $course_revenues[0]->fast_revenue + $webinar_revenues[0]->fast_revenue,
        );

        return $data;
    }
}

if(! function_exists('_get_commission_logs')){
    function _get_commission_logs($id){
        $voucher = _get_promoter_voucher($id);
        $course_purchases = Purchase_Item::where("purchase_items.payment_status","paid")
        ->where("purchase_items.voucher",$voucher->voucher_code)
        ->where("purchase_items.type","course")
        ->where("purchase_items.channel","promoter_promo")
        ->orderBy("purchase_items.updated_at","desc")
        ->leftJoin("courses","purchase_items.data_id","courses.id")
        ->get();
        $webinar_purchases = Purchase_Item::where("purchase_items.payment_status","paid")
        ->where("purchase_items.voucher",$voucher->voucher_code)
        ->where("purchase_items.type","webinar")
        ->orderBy("purchase_items.updated_at","desc")
        ->leftJoin("courses","purchase_items.data_id","courses.id")
        ->get();

        $data = array(
            "course_purchases" => $course_purchases,
            "webinar_purchases" => $webinar_purchases,
        );

        return $data;
    }
}

if(! function_exists('_get_payment_settings')){
    function _get_payment_settings($id){
        $promoter = Promoter::find($id);
        return json_decode($promoter->payout_settings);
    }
}

if(! function_exists('_get_payout_transaction')){
    function _get_payout_transaction(){
        $payouts = Payout::
        // where("payment_status","paid")
        where("data_id",Auth::guard('promoters')->user()->id)
        ->where("type","promoter");

        return $payouts->get();
    }
}




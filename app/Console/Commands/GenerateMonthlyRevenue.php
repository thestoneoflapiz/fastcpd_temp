<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use App\{Provider, Payout,Purchase,Purchase_Item,Course, Webinar};
use Carbon\Carbon;

class GenerateMonthlyRevenue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateMonthlyRevenue:monthlyRevenue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Generate Monthly Revenue for the FastCPD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $providers = Provider::where("status","approved")->get();
        foreach($providers as $provider){
            $course_revenues = Purchase_Item::select(
                                        DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                                        DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"),
                                        DB::raw("sum(purchase_items.promoter_revenue) as endorser_revenue"),
                                        DB::raw("sum(purchase_items.price) as price"),
                                        "purchase_items.purchase_id"
                                    )
                                    ->where("purchases.payment_status","paid")
                                    ->where("courses.provider_id",$provider->id)
                                    ->whereMonth("purchases.updated_at", '=', Carbon::now()->subMonth()->format("m"))
                                    ->whereYear("purchases.updated_at", '=', Carbon::now()->subMonth()->format("Y"))
                                    ->where("purchase_items.type","course")
                                    ->leftJoin("courses","purchase_items.data_id","courses.id")
                                    ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
                                    ->leftJoin("providers","courses.provider_id","providers.id")
                                    
                                    ->get();
            $webinar_revenues = Purchase_Item::select(
                                        DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                                        DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"),
                                        DB::raw("sum(purchase_items.promoter_revenue) as endorser_revenue"),
                                        DB::raw("sum(purchase_items.price) as price"),
                                        "purchase_items.purchase_id"
                                    )
                                    ->where("purchases.payment_status","paid")
                                    ->where("purchase_items.type","webinar")
                                    ->where("webinars.provider_id",$provider->id)
                                    ->whereMonth("purchases.updated_at", '=', Carbon::now()->subMonth()->format("m"))
                                    ->whereYear("purchases.updated_at", '=', Carbon::now()->subMonth()->format("Y"))
                                    ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                                    ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
                                    ->leftJoin("providers","webinars.provider_id","providers.id")
                                    
                                    ->get();
                                      
           if(($course_revenues[0]["price"] == null || $course_revenues[0]["price"] == 0) && ($webinar_revenues[0]["price"] == null || $webinar_revenues[0]["price"] == 0)){
                $payout = new Payout;
                $payout->type = "provider";
                $payout->data_id = $provider->id;
                $payout->date_from = Carbon::now()->subMonth()->startOfMonth();
                $payout->date_to = Carbon::now()->subMonth()->lastOfMonth();
                $payout->price_paid = 0;
                $payout->fast_revenue = 0;
                $payout->provider_revenue = 0;
                $payout->promoter_revenue = 0;
                $payout->created_at =  date("Y-m-d H:i:s");
                $payout->updated_at = date("Y-m-d H:i:s");
                $payout->status = "waiting";
                $payout->save();
           }else{
                $payout = new Payout;
                $payout->type = "provider";
                $payout->data_id = $provider->id;
                $payout->date_from = Carbon::now()->subMonth()->startOfMonth();
                $payout->date_to = Carbon::now()->subMonth()->lastOfMonth();
                $payout->price_paid = $course_revenues[0]["price"] + $webinar_revenues[0]["price"];
                $payout->fast_revenue = $course_revenues[0]["fast_revenue"] + $webinar_revenues[0]["fast_revenue"];
                $payout->provider_revenue = $course_revenues[0]["provider_revenue"] + $webinar_revenues[0]["provider_revenue"];
                $payout->promoter_revenue = $course_revenues[0]["endorser_revenue"] + $webinar_revenues[0]["endorser_revenue"];
                $payout->created_at =  date("Y-m-d H:i:s");
                $payout->updated_at = date("Y-m-d H:i:s");
                $payout->status = "waiting";
                $payout->save();
           }
           //_send_notification_email($provider->email,'payout_completed',$provider->id,$payout->id);
        }

        $course_revenues = Purchase_Item::select(
                                DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                                DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"),
                                DB::raw("sum(purchase_items.promoter_revenue) as endorser_revenue"),
                                DB::raw("sum(purchase_items.price) as price"),
                                "purchase_items.purchase_id"
                            )
                            ->where("purchases.payment_status","paid")
                            ->whereMonth("purchases.updated_at", '=', Carbon::now()->subMonth()->format("m"))
                            ->whereYear("purchases.updated_at", '=', Carbon::now()->subMonth()->format("Y"))
                            ->where("purchase_items.type","course")
                            ->leftJoin("courses","purchase_items.data_id","courses.id")
                            ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
                            ->leftJoin("providers","courses.provider_id","providers.id")
                            
                            ->get();
        $webinar_revenues = Purchase_Item::select(
                                DB::raw("sum(purchase_items.provider_revenue) as provider_revenue"),
                                DB::raw("sum(purchase_items.fast_revenue) as fast_revenue"),
                                DB::raw("sum(purchase_items.promoter_revenue) as endorser_revenue"),
                                DB::raw("sum(purchase_items.price) as price"),
                                "purchase_items.purchase_id"
                            )
                            ->where("purchases.payment_status","paid")
                            ->where("purchase_items.type","webinar")
                            ->whereMonth("purchases.updated_at", '=', Carbon::now()->subMonth()->format("m"))
                            ->whereYear("purchases.updated_at", '=', Carbon::now()->subMonth()->format("Y"))
                            ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                            ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
                            ->leftJoin("providers","webinars.provider_id","providers.id")
                            
                            ->get();
        if(($course_revenues[0]["price"] == null || $course_revenues[0]["price"] == 0) && ($webinar_revenues[0]["price"] == null || $webinar_revenues[0]["price"] == 0)){
            $payout = new Payout;
            $payout->type = "fast";
            $payout->data_id =null;
            $payout->date_from = Carbon::now()->subMonth()->startOfMonth();
            $payout->date_to = Carbon::now()->subMonth()->lastOfMonth();
            $payout->price_paid = 0;
            $payout->fast_revenue = 0;
            $payout->provider_revenue = 0;
            $payout->promoter_revenue = 0;
            $payout->created_at =  date("Y-m-d H:i:s");
            $payout->updated_at = date("Y-m-d H:i:s");
            $payout->status = "waiting";
            $payout->save();
        }else{
            $payout = new Payout;
            $payout->type = "fast";
            $payout->data_id = null;
            $payout->date_from = Carbon::now()->subMonth()->startOfMonth();
            $payout->date_to = Carbon::now()->subMonth()->lastOfMonth();
            $payout->price_paid = $course_revenues[0]["price"] + $webinar_revenues[0]["price"];
            $payout->fast_revenue = $course_revenues[0]["fast_revenue"] + $webinar_revenues[0]["fast_revenue"];
            $payout->provider_revenue = $course_revenues[0]["provider_revenue"] + $webinar_revenues[0]["provider_revenue"];
            $payout->promoter_revenue = $course_revenues[0]["endorser_revenue"] + $webinar_revenues[0]["endorser_revenue"];
            $payout->created_at =  date("Y-m-d H:i:s");
            $payout->updated_at = date("Y-m-d H:i:s");
            $payout->status = "waiting";
            $payout->save();
        }
        
        $this->info('GenerateMonthlyRevenue:Monthly Revenue Command Run successfully!');
    }
}

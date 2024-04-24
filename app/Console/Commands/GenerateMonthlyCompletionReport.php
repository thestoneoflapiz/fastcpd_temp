<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use App\{Provider, Payout,Purchase,Purchase_Item,Course,CompletionReport,Webinar};
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateMonthlyCompletionReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateMonthlyCompletionReport:monthlyCompletionReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Generate Monthly Completion Report for the FastCPD';

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
          
             $courses = Course::where("provider_id",$provider->id)->where("fast_cpd_status","live")->get();
             $webinars = Webinar::where("provider_id",$provider->id)->get();
        //     dd($courses);
             if($courses){
                foreach($courses as $course){
                    
                    $participants = Purchase_Item::select(DB::raw("count(id) as participant_count"))
                                                ->where("payment_status","paid")
                                                ->where("fast_status","complete")
                                                ->where("data_id",$course->id)
                                                ->where("type","course")
                                                ->whereMonth("updated_at", '=', Carbon::now()->subMonth()->format("m"))
                                                ->whereYear("updated_at", '=', Carbon::now()->subMonth()->format("Y"))
                                                ->first();
                                                  
                    CompletionReport::insert([
                        "provider_id" => $provider->id,
                        "type" => "course",
                        "data_id" => $course->id,
                        "participants" => $participants->participant_count,
                        "completion_date" => date("Y-m-d H:i:s",strtotime("-1 months")),
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    
                }
            }
            if($webinars){
                foreach($webinars as $webinar){
                    $participants = Purchase_Item::select(DB::raw("count(id) as participant_count"))
                                                ->where("payment_status","paid")
                                                ->where("fast_status","complete")
                                                ->where("data_id",$webinar->id)
                                                ->where("type","webinar")
                                                //->whereMonth("updated_at", '=', Carbon::now()->subMonth()->format("m"))
                                                //->whereYear("updated_at", '=', Carbon::now()->subMonth()->format("Y"))
                                                ->whereMonth("updated_at", '=', Carbon::now()->format("m"))
                                                ->whereYear("updated_at", '=', Carbon::now()->format("Y"))
                                                ->first();
                                                     
                    CompletionReport::insert([
                        "provider_id" => $provider->id,
                        "type" => "webinar",
                        "data_id" => $webinar->id,
                        "participants" => $participants->participant_count,
                        "completion_date" => date("Y-m-d H:i:s",strtotime("-1 months")),
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        $this->info('GenerateMonthlyCompletionReport:Monthly Completion Report Command Run successfully!');
    }
}

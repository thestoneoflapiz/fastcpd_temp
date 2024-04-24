<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{
    Webinar, Webinar_Series, Webinar_Session,
};

class WebinarLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webinar:live';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        /** 
         * go back to published or ended
         */
        $live_webinars = Webinar::whereIn("fast_cpd_status", ["approved", "live", "published"])->get();
        foreach ($live_webinars as $key => $webinar) {
            $session_today = Webinar_Session::select("session_date")
                ->whereDate("session_date", "=", date("Y-m-d"))
                ->where("webinar_id", "=", $webinar->id)->first();

            if($session_today){
                $webinar->fast_cpd_status = "live";
                $webinar->updated_at = date("Y-m-d H:i:s");
                $webinar->save();
                $this->info("{$webinar->id} has started the session today.");
            }else{
                $next_sessions = Webinar_Session::select("session_date")
                    ->whereDate("session_date", ">", date("Y-m-d"))
                    ->where("webinar_id", "=", $webinar->id)->first();
                    
                if($next_sessions){
                    $webinar->fast_cpd_status = "published";
                    $webinar->updated_at = date("Y-m-d H:i:s");
                    $webinar->save();
                    $this->info("{$webinar->id} is published today.");
                }else{
                    $webinar->fast_cpd_status = "ended";
                    $webinar->updated_at = date("Y-m-d H:i:s");
                    $webinar->save();
                    $this->info("{$webinar->id} has ended today.");
                }
            }
        }
    }
}

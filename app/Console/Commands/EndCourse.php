<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{
    Course, Webinar, Webinar_Session,
};

class EndCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating course that comes to end their session';

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
         * Course
         */
        $courses = Course::whereDate("session_end", "<=", date("Y-m-d"))
        ->where("fast_cpd_status", "=", "live")->get();

        foreach ($courses as $key => $course) {
            $course->fast_cpd_status = "ended";
            $course->save();
            
            $this->info("{$course->id} has ended the session today.");
        }
        /**
         * Webinar
         */
        $webinars = Webinar::whereIn("fast_cpd_status",["published", "live", "approved"])->get();
        foreach ($webinars as $webinar) {
            $session_last = Webinar_Session::select("session_date")->where([
                "webinar_id" => $webinar->id,
                "deleted_at" => null
            ])->orderBy("session_date", "desc")->first();

            if($session_last->session_date < date("Y-m-d")){
                $webinar->fast_cpd_status = "ended";
                $webinar->save();
                
                $this->info("{$course->id} has ended the session today.");
            }
        }
    }
}

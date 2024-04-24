<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{Course, Webinar};

class LiveCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:live';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating course that comes to start their session';

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
         * getting published courses
         */
        $pcourses = Course::whereDate("published_at", "<=", date("Y-m-d"))
        ->where("fast_cpd_status", "=", "approved")->get();

        foreach ($pcourses as $key => $pcourse) {
            $pcourse->fast_cpd_status = "published";
            $pcourse->save();
            
            $this->info("{$pcourse->id} is published today.");
        }
        /**
         * live courses
         */
        $courses = Course::whereDate("session_start", "<=", date("Y-m-d"))
        ->where("fast_cpd_status", "=", "published")->get();

        foreach ($courses as $key => $course) {
            $course->fast_cpd_status = "live";
            $course->save();
            
            $this->info("{$course->id} has started the session today.");
        }
    }
}

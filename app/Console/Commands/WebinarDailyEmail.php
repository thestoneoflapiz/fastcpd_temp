<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{
    User, Purchase_Item,
    Webinar, Webinar_Series, Webinar_Session,
};
use Illuminate\Support\Facades\{Mail};
use App\Mail\{
    NotificationMail
};

use DateTime;

class WebinarDailyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webinar:dailyemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for updates users who purchased webinars that the webinar is about to live in {number of days or hours}';

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
        $users_who_purchased = Purchase_Item::select("user_id", "data_id", "schedule_id", "schedule_type")->where([
            "type" => "webinar",
            "payment_status" => "paid",
            "fast_status" => "incomplete",
        ])->get();

        foreach ($users_who_purchased as $user) {
            if($user->schedule_type=="day"){
                $session = Webinar_Session::select("session_date")->find($user->schedule_id);
                if($session){
                    $user_info = User::select("first_name", "name", "email")->find($user->user_id);
                    $webinar = Webinar::select("id", "url", "title")->find($user->data_id);

                    $today = new DateTime();
                    $theDay = new DateTime($session->session_date);
                    $interval = $today->diff($theDay);
                    $days = $interval->format('%a');

                    if($days==2){
                        $data = array(
                            'subject' => "\"{$webinar->title}\" live in 3 days!",
                            'body' => "Hi ".($user_info->first_name ?? $user_info->name)."<br>The webinar you've purchased(<b>{$webinar->title}</b>) will be live in 3 days! Are you ready?",
                            'recipient'=> "",
                            'link_button' => "",
                            'label_button' => "",
                        );

                        Mail::to($user_info->email)->send(new NotificationMail($data));

                    }else if($days==0){
                        $data = array(
                            'subject' => "\"{$webinar->title}\" live TOMORROW!",
                            'body' => "Hi ".($user_info->first_name ?? $user_info->name)."<br>The webinar you've purchased(<b>{$webinar->title}</b>) will be live <b>TOMORROW</b>! Are you ready?",
                            'recipient'=> "",
                            'link_button' => "",
                            'label_button' => "",
                        );

                        Mail::to($user_info->email)->send(new NotificationMail($data));
    
                    }else{
                        if(date("Y-m-d", strtotime($session->session_date)) == date("Y-m-d")){
                            $data = array(
                                'subject' => "\"{$webinar->title}\" live TODAY!!",
                                'body' => "Hi ".($user_info->first_name ?? $user_info->name)."<br>The webinar you've purchased(<b>{$webinar->title}</b>) is live <b>TODAY</b>! Are you ready?",
                                'recipient'=> "",
                                'link_button' => config("app.link")."/webinar/live/{$webinar->url}",
                                'label_button' => "VISIT LIVE WEBINAR NOW",
                            );

                            Mail::to($user_info->email)->send(new NotificationMail($data));
                        }
                    }
                }

                
            }else{
                // as series
                $series = Webinar_Series::select("sessions")->find($user->schedule_id);
                if($series && $series->sessions){
                    $session = Webinar_Session::select("session_date")
                        ->whereDate("session_date", ">=", date("Y-m-d"))
                        ->whereIn("id", json_decode($series->sessions))->first();

                    if($session){
                        $user_info = User::select("first_name", "name", "email")->find($user->user_id);
                        $webinar = Webinar::select("id", "url", "title")->find($user->data_id);

                        $today = new DateTime();
                        $theDay = new DateTime($session->session_date);
                        $interval = $today->diff($theDay);
                        $days = $interval->format('%a');

                        if($days==2){
                            $data = array(
                                'subject' => "\"{$webinar->title}\" live in 3 days!",
                                'body' => "Hi ".($user_info->first_name ?? $user_info->name)."<br>The webinar you've purchased(<b>{$webinar->title}</b>) will be live in 3 days! Are you ready?",
                                'recipient'=> "",
                                'link_button' => "",
                                'label_button' => "",
                            );
    
                            Mail::to($user_info->email)->send(new NotificationMail($data));
    
                        }else if($days==0){
                            $data = array(
                                'subject' => "\"{$webinar->title}\" live TOMORROW!",
                                'body' => "Hi ".($user_info->first_name ?? $user_info->name)."<br>The webinar you've purchased(<b>{$webinar->title}</b>) will be live <b>TOMORROW</b>! Are you ready?",
                                'recipient'=> "",
                                'link_button' => "",
                                'label_button' => "",
                            );
    
                            Mail::to($user_info->email)->send(new NotificationMail($data));
        
                        }else{
                            if(date("Y-m-d", strtotime($session->session_date)) == date("Y-m-d")){
                                $data = array(
                                    'subject' => "\"{$webinar->title}\" live TODAY!!",
                                    'body' => "Hi ".($user_info->first_name ?? $user_info->name)."<br>The webinar you've purchased(<b>{$webinar->title}</b>) is live <b>TODAY</b>! Are you ready?",
                                    'recipient'=> "",
                                    'link_button' => config("app.link")."/webinar/live/{$webinar->url}",
                                    'label_button' => "VISIT LIVE WEBINAR NOW",
                                );
        
                                Mail::to($user_info->email)->send(new NotificationMail($data));
                            }
                        }
                    }
                }
            }
        }
    }
}

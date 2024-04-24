<?php

namespace App\Jobs;

use App\{
    Video, Course, Section, 
    CLogs,Instructor_Permissions,
    Purchase, Purchase_Item,User, Provider,
    Webinar,
};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\{Storage, Mail};
use App\Mail\{NotificationMail};

class EmailBigQueries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 0;

    protected $recipient;
    protected $module;
    protected $reference_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipient, $module, $reference_id)
    {
        $this->recipient = $recipient;
        $this->module = $module;
        $this->reference_id = $reference_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch($this->module) {
            case "pending_purchase":
                $segregated_purchases = [];
                $transaction = Purchase::select("id")->where('reference_number',$this->reference_id)->first();
                $user = User::where('email', $this->recipient)
                            ->where('deleted_at', null)
                            ->first();
                $table = _get_puchase_details($this->reference_id);
                $subject="Your purchase is almost complete!";
                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Please follow the instructions sent by our payment partner to complete your payment. Check your inbox or spam folder for the instruction 
                email from ".ucfirst($transaction->payment_gateway).". <br/><br/>
                Purchase(s):<br/>
                <div class='center' style='margin:auto;'>
                    <table width='90%'>
                    ".$table."
                    </table>
                </div>
                <br/><br/>
                You're almost done! We'll send you a confirmation email once we receive payment.<br/><br/>
                You can view your payment transactions in your account overview. Click the button to access your account.<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "/profile/settings",
                    'label_button' => "GO TO  OVERVIEW",
                );

                Mail::to($user->email)->send(new NotificationMail($data));
            break;

            case "confirmed_purchase":

                $transaction = Purchase::select("id")->where('reference_number',$this->reference_id)->first();
                $transaction_items = Purchase_item::where('purchase_id',$transaction->id)->get();
                $user = User::where('email', $this->recipient)
                            ->where('deleted_at', null)
                            ->first();
                $table = _get_puchase_details($this->reference_id);
                $subject = "Thank you for your purchase!";
                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Thank You! Your purchase has been confirmed! You now have access to these courses.
                <br/><br/>Purchase(s):
                <div class='center' style='margin:auto;'>
                    <table width='90%'>
                    ".$table."
                    </table>
                </div>
                 <br/><br/>You can access your purchases by visiting your overview page in the settings panel of your account. <br/><br/>
                 You can now start watching and earn CPD units. Click the button to go to your list of purchases<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "/",
                    'label_button' => "GO TO COURSES",
                );
                Mail::to($user->email)->send(new NotificationMail($data));


                ////////////////PROVIDERS MAIL START//////////////////////////////////////////

                foreach($transaction_items as $item){
                    if($item->type=="course"){
                        $data_record = Course::select("id", "url", "title", "course_poster as poster", "provider_id")->find($item->data_id);
                    }else{
                        $data_record = Webinar::select("id", "url", "title", "webinar_poster as poster", "provider_id")->find($item->data_id);
                    }

                    $provider = User::where("provider_id", $data_record->provider_id)->first();
                    $data = array(
                        "title" => $data_record->title,
                        "poster" => $data_record->poster,
                        "credited_cpd_units" => $item->credited_cpd_units,
                        "total_amount" => $item->total_amount,
                        "price" => $item->price,
                        "discount" => $item->discount,
                        "voucher" => $item->voucher,
                        "user_id" => $item->user_id,
                    );

                    $segregated_purchases[$provider->id][] = $data;
                }

                foreach($segregated_purchases as $provider_id => $purchases){
                    $table = "";
                    $total = 0;
                    $user = User::find($provider_id);
                    foreach($purchases as $purchase ){
                        $units = "";
                        if($purchase['credited_cpd_units']){
                            foreach(json_decode($purchase['credited_cpd_units']) as $unit){
                                $units .= $unit->title." - ".$unit->units." <br/>";
                            }
                        }
                        if($purchase['discount'] != null){
                            $price = "Php ".$purchase['total_amount']."<br/><del>Php ".$purchase['price']."</del>";
                        }else{
                            $price = "Php ".$purchase['total_amount']."<br/>";
                        }

                        $table .= "<tr >
                                    <td class='center'><img alt='FastCPD Courses Webinars' src='".$purchase['poster']."' width='150'></td>
                                    <td class='left'>
                                        <b>".$purchase['title']."</b> <br/>".$units."
                                    </td>
                                    <td class='right'>".$price."</td>
                                </tr>";
                        $total += $purchase['total_amount'];
                    }

                    $table .="<tr>
                                <td colspan='3' class='right'> TOTAL: ".$total."</td>
                            </tr>";
                    $subject="New purchase!";
                    $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                    You got a new order for the following item(s).You can view your item's performance and sales in the performance overview of the provider portal. <br/><br/>
                    Purchase(s):<br/>
                    <div class='center' style='margin:auto;'>
                    <table width='90%'>
                        ".$table."
                    </table>
                    </div>
                    <br/><br/>
                    Keep on sharing and marketing your courses or webinars to other professionals!<br/><br/>";
                    
                    $data = array(
                        'subject' => $subject,
                        'body' => $body,
                        'recipient'=> $user,
                        'link_button' => "notification/redirect_email/course_performance/".$provider_id,
                        'label_button' => "GO TO  PERFORMANCE",
                    );

                    Mail::to($user->email)->send(new NotificationMail($data));
                    
                }
                ////////////////PROVIDERS MAIL END//////////////////////////////////////////
                
            break;

            case "cancelled_purchase":
                $user = User::where('email', $this->recipient)
                            ->where('deleted_at', null)
                            ->first();

                $table = _get_puchase_details($this->reference_id);
                $subject = "Your purchase was cancelled";
                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Your purchase of the following items has been cancelled or has failed:<br/>
                Purchase(s):
                <div class='center' style='margin:auto;'>
                 <table width='90%'>
                    ".$table."
                 </table>
                </div>
                 <br/><br/>These are some of the reasons why your purchase was cancelled:<br/>
                 - Payment failed or was not completed on time <br/>
                 - You closed or your connection was lost during the payment confirmation<br/>
                 - You cancelled your order manually <br/><br/>
                 If you would like to clarfiy this matter, please email us at help@fastcpd.com<br/>";

                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "",
                    'label_button' => ""
                );
                Mail::to($user->email)->send(new NotificationMail($data));
                
              break;
            default:
        }
    }

        
}

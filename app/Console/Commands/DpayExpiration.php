<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{Purchase, Purchase_Item, User, CLogs};

use App\Jobs\{EmailBigQueries};

class DpayExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dragonpay:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Control over dragonpay payments that are expired:7Days';

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
        $date = new \DateTime();
        $date->modify('-7 days');
        $previous_7_days = $date->format('Y-m-d');

        $records = Purchase::where("payment_gateway", "=", "dragonpay")
        ->whereDate("created_at", "<", $previous_7_days)
        ->whereIn("payment_status", ["waiting", "pending"])->get();

        foreach ($records as $key => $record) {
            CLogs::insert([
                "message" => "$record->id dragonpay payment has expired"
            ]);

            $record->payment_status = "failed";
            $record->updated_at = date("Y-m-d H:i:s");
            $items = Purchase_Item::where("purchase_id", "=", $record->id)->get();
            foreach ($items as $item) {
                $item->payment_status = "failed";
                $item->updated_at = date("Y-m-d H:i:s");
                _update_referral_voucher($item->voucher, $item->discount, false);

                $item->save();
            }
            $record->save();
            
            _notification_insert("purchase", $record->user_id, $record->reference_number, "Payment Failed", "Your transaction payment with ref#{$record->reference_number} has failed", "/profile/settings");
            $user = User::find($record->user_id);
            if($user){
                dispatch(new EmailBigQueries($user->email, "cancelled_purchase", $record->reference_number));
                
            }
            $this->info("{$record->id} is 7 days late...");
        }
    }
}

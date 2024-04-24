<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{
    User, Referral, Referral_Code,
};

class VerifyReferral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:referral';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing status of verified referrals';

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
        $waiting_referrals = Referral::where(["status"=>"waiting"])->get();
        foreach ($waiting_referrals as $key => $referral) {
            $is_verified = User::select("id")->where([
                "id" => $referral->referral_id,
                "status" => "active",
            ])->where("email_verified_at", "!=", NULL)->where("prc_id", "!=", NULL)->orderBy("created_at", "desc")->first();

            if($is_verified){
                Referral::where("referral_id", $referral->referral_id)->update(["status"=>"approved"]);
                $referer = Referral_Code::where("referer_id", $referral->referer_id)->orderBy("created_at", "desc")->first();
                if($referer){
                    $referer->total_redeemed=$referer->total_redeemed+1;
                    $referer->save();
                }
                $this->info("Referral#{$referral->referral_id} is verified and approved.");
            }
        }
    }
}

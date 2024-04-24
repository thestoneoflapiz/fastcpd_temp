<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{Voucher, CLogs};

class VoucherEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voucher:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating vouchers that comes to end their session';

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
        $vouchers = Voucher::where("session_end", "<", date("Y-m-d"))
        ->where("status", "=", "active")->where("type", "!=", "rc_once_applied")->where("channel", "!=", "promoter_promo")->get();

        foreach ($vouchers as $key => $voucher) {
            $voucher->status = "ended";
            $voucher->save();
            
            $this->info("{$voucher->id} has ended the session today.");
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{Voucher, CLogs};

class VoucherStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voucher:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating vouchers that comes to start their session';

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
        $vouchers = Voucher::where("status", "=", "upcoming")
        ->where("session_start", "<=", date("Y-m-d"))->where("type", "!=", "rc_once_applied")->get();

        foreach ($vouchers as $key => $voucher) {
            $voucher->status = "active";
            $voucher->save();
            
            $this->info("{$voucher->id} has started the session today.");
        }
    }
}

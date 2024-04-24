<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\{
    Provider, Revenue_Sharing, CLogs,
};

class Revenue_Sharing_Default extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revenue_sharing:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executing default revenue_sharing';

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
        Revenue_Sharing::truncate();

        $providers = Provider::select("id")->get();
        foreach ($providers as $provider) {
            Revenue_Sharing::insert([
                [
                    "provider_id" => $provider->id,
                    "type" => "course",
                    "fast_revenue" => "50",
                    "provider_revenue" => "75",
                    "promoter_revenue" => "30",
                    "created_at" => date("Y-m-d H:i:s"),
                ],
                [
                    "provider_id" => $provider->id,
                    "type" => "webinar",
                    "fast_revenue" => "15",
                    "provider_revenue" => "90",
                    "promoter_revenue" => "30",
                    "created_at" => date("Y-m-d H:i:s"),
                ]
            ]);
        }

        CLogs::insert([
            "message" => "Revenue Sharing has been truncated and in default for every provider."
        ]);

        $this->info("Revenue Sharing has been truncated and in default for every provider.");
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReferralCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('referer_id');
            $table->string('referral_code');
            $table->string('voucher_code');
            $table->json('discount')->nullable();
            $table->integer('total_redeemed')->default(0);
            $table->enum('status', ["complete", "waiting", "blocked"])->default("waiting");

            $table->dateTime('created_at');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

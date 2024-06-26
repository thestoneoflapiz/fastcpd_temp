<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('channel', ['fast_promo', 'provider_promo', 'promoter_promo']);
            $table->integer('provider_id')->nullable();
            $table->json('data_id')->nullable();

            $table->float('discount');
            $table->string('voucher_code');
            $table->text('description')->nullable();

            $table->date('session_start')->nullable();
            $table->date('session_end')->nullable();

            $table->enum('type', ["auto_applied", "auto_applied_when_loggedin", "manual_apply", "rc_once_applied"]);
            $table->enum('status', ["upcoming", "active", "ended", "delete", "in-review", "rejected"]);
            $table->integer("created_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}

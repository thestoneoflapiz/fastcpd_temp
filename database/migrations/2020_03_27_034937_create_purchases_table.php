<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('reference_number')->nullable();

            $table->json('vouchers')->nullable();
            $table->float('total_discount')->nullable();
            
            $table->float('processing_fee')->nullable();
            $table->float('total_amount');

            $table->enum('payment_gateway', ["paymongo", "dragonpay"])->nullable();
            $table->string('payment_client_id')->nullable(); 
            $table->string('payment_client_key')->nullable(); 
            $table->enum('payment_method', [
                "card", "gcash", "grab_pay",
                "bayd", "cebl", "mlh", "smr", 
                "aub", "bdrx", "cbcx", "ewxb", "lbxb", "mbxb", "pnb", "rcxb", "rsbb", "ubxb", "ucxb", "sbcb", 
                "bdo", "bpib", "cbc", "lbpa", "mayb", "mbtc", "psb", "rcbc", "rsb", "ubpb", "ucpb", "pnxb"
            ])->nullable();
            $table->dateTime('payment_at')->nullable();
            $table->enum('payment_status', ["waiting", "pending", "paid", "cancelled", "failed"])->default("waiting");
            $table->text('payment_notes')->nullable();
            
            $table->enum('fast_status', ["confirmed", "waiting"])->default("waiting");

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
        Schema::dropIfExists('purchases');
    }
}

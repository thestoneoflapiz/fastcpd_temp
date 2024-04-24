<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MyCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_cart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            
            $table->enum('type', ["webinar", "course"]);
            $table->integer('data_id');

            $table->json('accreditation')->nullable();
            $table->float('price');

            $table->float('discounted_price')->nullable();
            $table->float('discount')->nullable();
            $table->string('voucher')->nullable();
            $table->enum('channel', ['fast_promo', 'provider_promo', 'promoter_promo'])->default("fast_promo");

            $table->float('total_amount')->default(0);

            $table->enum('offering_type', ['with', 'without'])->nullable();
            $table->enum('schedule_type', ['day', 'series'])->nullable();
            $table->integer('schedule_id')->nullable();

            $table->enum('status', ["active", "checkout"])->default("active");
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
        //
    }
}

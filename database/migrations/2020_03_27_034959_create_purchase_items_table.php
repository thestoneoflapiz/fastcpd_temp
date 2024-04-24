<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('purchase_id');
            $table->integer('user_id');
            $table->enum('type', ["webinar", "course"]);
            $table->integer('data_id');

            $table->json('credited_cpd_units')->nullable();
            $table->float('price')->default(0);

            $table->float('discounted_price')->nullable();
            $table->float('discount')->nullable();
            $table->string('voucher')->nullable();
            $table->enum('channel', ['fast_promo', 'provider_promo', 'promoter_promo'])->default("fast_promo");

            $table->float('total_amount')->default(0);

            $table->enum('offering_type', ['with', 'without'])->nullable();
            $table->enum('schedule_type', ['day', 'series'])->nullable();
            $table->integer('schedule_id')->nullable();
            
            $table->float('fast_revenue')->default(0);
            $table->float('provider_revenue')->default(0);
            $table->float('promoter_revenue')->default(0);
            
            $table->enum('payment_status', ["waiting", "pending", "paid", "cancelled", "failed"])->default("waiting");
            $table->enum('fast_status', ["complete", "incomplete", "failed", "passed"])->default("incomplete");

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
        Schema::dropIfExists('purchase_items');
    }
}

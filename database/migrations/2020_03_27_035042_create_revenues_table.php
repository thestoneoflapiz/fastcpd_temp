<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('provider_id');
            $table->float('voucher_price', 8, 2);
            $table->longText('comments');
            $table->dateTime('date_from');
            $table->dateTime('date_to');
            $table->float('price_paid', 8, 2);
            $table->float('fast_revenue', 8, 2);
            $table->float('provider_revenue', 8, 2);
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
        Schema::dropIfExists('revenues');
    }
}

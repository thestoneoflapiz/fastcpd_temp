<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->enum('type', ["provider", "fast","promoter"]);
                $table->integer('data_id')->nullable();
                $table->dateTime('date_from');
                $table->dateTime('date_to');
                $table->float('price_paid', 8, 2);
                $table->float('fast_revenue', 8, 2);
                $table->float('provider_revenue', 8, 2);
                $table->float('promoter_revenue', 8, 2);
                $table->enum('status', ["waiting", "on-hold","paid"]);
                $table->longText('notes')->nullable();
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
        Schema::dropIfExists('payouts');
    }
}

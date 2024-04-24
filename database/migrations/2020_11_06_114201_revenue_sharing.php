<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RevenueSharing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue_sharing', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('provider_id');
            $table->enum('type', ["webinar", "course"]);

            $table->integer('fast_revenue')->default(50);
            $table->integer('provider_revenue')->default(85);
            $table->integer('promoter_revenue')->default(30);

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

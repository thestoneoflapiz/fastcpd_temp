<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SystemRatingReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_rating_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['profession', 'provider', 'course', 'instructor']);
            $table->integer('data_id');
            $table->float('rating');
            $table->string('week');
            $table->string('month');
            $table->string('year');
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

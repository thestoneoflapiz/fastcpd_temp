<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImageInterventions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_interventions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->enum('type', ['course', 'provider', 'user', 'landing', 'announcement', 'webinar']);
            $table->integer('data_id');

            $table->text('original_size')->nullable();
            $table->text('medium_size')->nullable();
            $table->text('small_size')->nullable();
            
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->index(['type', 'data_id']);
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

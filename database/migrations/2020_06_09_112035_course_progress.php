<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CourseProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('course_progress', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('course_id');
            $table->integer('section_id');
            $table->enum('type', ['video', 'article', 'quiz']);
            $table->integer('data_id');
            $table->integer('played_time')->nullable()->comment('by seconds'); // for video
            // for Quiz { "total" => int, "items" => int, "percentage" => int }
            $table->json('quiz_overall')->nullable()->comment('{ "total" => int, "items" => int, "percentage" => int }');

            $table->enum('status', ['in-progress', 'completed', 'passed', 'failed'])->default('in-progress');
            $table->datetime('deleted_at')->nullable();
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

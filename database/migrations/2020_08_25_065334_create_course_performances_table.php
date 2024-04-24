<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_performances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("course_id");
            $table->integer("user_id");
            $table->enum('valuable_information',["yes", "no", "unsure"])->nullable();
            $table->enum('concepts_clear',["yes", "no", "unsure"])->nullable();
            $table->enum('instructor_delivery',["yes", "no", "unsure"])->nullable();
            $table->enum('opportunities',["yes", "no", "unsure"])->nullable();
            $table->enum('expectations',["yes", "no", "unsure"])->nullable();
            $table->enum('knowledgeable',["yes", "no", "unsure"])->nullable();
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
        Schema::dropIfExists('course_performances');
    }
}

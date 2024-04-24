<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructionalDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructional_designs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ["webinar", "course"])->nullable();
            $table->integer('data_id')->nullable();
            $table->json('objectives')->nullable();
            $table->string('section_objective')->nullable();
            $table->json('instructors')->nullable();
            $table->integer('video_length')->nullable();
            $table->integer('video_counter')->nullable();
            $table->integer('article_counter')->nullable();
            $table->string('evaluation_quiz_count')->nullable();
            $table->string('evaluation_question_count')->nullable();

            $table->string('created_by')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructional_designs');
    }
}

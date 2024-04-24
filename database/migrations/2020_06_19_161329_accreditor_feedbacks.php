<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccreditorFeedbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accreditor_feedbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id');
            $table->integer('accreditor_id');
            $table->string('credited_units')->nullable();
            $table->string('accreditation_number')->nullable();
            $table->text('compliance')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['approved', 'in-review', 'draft']);
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

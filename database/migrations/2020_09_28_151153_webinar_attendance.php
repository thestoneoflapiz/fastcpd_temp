<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WebinarAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('webinar_id');
            $table->integer('session_id');
            $table->integer('user_id');

            $table->dateTime('session_in')->nullable();
            $table->dateTime('session_out')->nullable();

            $table->dateTime('created_at');
            $table->string('created_by')->default(0);
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

        Schema::dropIfExists('webinar_attendance');
    }
}

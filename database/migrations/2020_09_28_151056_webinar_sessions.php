<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WebinarSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('webinar_id');

            $table->date('session_date');
            $table->json('sessions');
            $table->string('link', 500)->nullable();

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
        Schema::dropIfExists('webinar_sessions');
    }
}

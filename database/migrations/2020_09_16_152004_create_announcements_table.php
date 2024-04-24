<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('target_audience', ['general','students', 'provider', 'instructor', 'course']);
            $table->json('recipients')->nullable();
            $table->string('title');
            $table->longText('message');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->enum('banner_state', ['primary','secondary', 'danger', 'warning', 'success', 'info', 'light', 'dark']);
            $table->enum('status', ['active','deleted', 'expired', 'inactive','pending']);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('created_by');

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
        Schema::dropIfExists('announcements');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('module', ['course_creation', 'provider', 'course', 'instructor', 'voucher', 'purchase']);
            $table->integer('recipient')->nullable();
            $table->string('data_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('link')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('read_at')->nullable();
            $table->string('seen_at')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccreditedUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_accredited_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('course_id');
            $table->string('user_id');
            $table->string('status');
            $table->string('unit');
            $table->enum('type', ['flexible', 'a', 'b','c']);
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
        Schema::dropIfExists('user_accredited_units');
    }
}

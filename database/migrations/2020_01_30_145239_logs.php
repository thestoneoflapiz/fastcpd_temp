<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Logs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('module');
            $table->string('activity');
            $table->string('remarks');
            $table->integer('data_id')->default(0);
            $table->integer('by')->default(0);
            $table->datetime('created_at');
            $table->datetime('read_at');
            $table->index(['module', 'by']);
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

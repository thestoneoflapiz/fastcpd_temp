<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('title');
            $table->enum('type', ["webinar", "course"]);
            $table->integer('data_id');
            $table->longText('note')->nullable();
            $table->string('url');
            $table->timestamps();

            $table->string('created_by')->default(0);
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
        Schema::dropIfExists('handouts');
    }
}

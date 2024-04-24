<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('section_id');
            $table->integer('sequence_number');
            $table->string('title');
            $table->string('cdn_url')->nullable();
            $table->string('filename')->nullable();
            $table->string('size')->nullable();
            $table->string('length')->default(0);
            $table->string('uploading_status')->nullable();
            $table->string('poster');
            $table->string('thumbnail')->nullable();
            $table->json('resolution')->nullable();
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
        Schema::dropIfExists('videos');
    }
}

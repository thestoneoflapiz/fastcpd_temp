<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpd_units', function (Blueprint $table) {
            $table->bigIncrements('unit_id');
            $table->json('course_id');
            $table->json('profession_id');
            $table->string('amount');
            $table->string('accreditation_number');
            $table->enum('status', ['active', 'inactive', 'delete']);
            $table->enum('type', ['flexible', 'a', 'b','c']);
            $table->float('price', 8, 2);
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
        Schema::dropIfExists('cpd_units');
    }
}

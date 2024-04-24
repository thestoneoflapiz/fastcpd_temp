<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RequestContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ["webinar", "course"]);
            $table->integer('data_id');

            $table->enum('user_type', ["user", "non-user"]);
            
            $table->string("first_name");
            $table->string("middle_name")->nullable();
            $table->string("last_name");
            
            $table->string("email")->nullable();
            $table->string("contact")->nullable();
            $table->string("address")->nullable();
            
            $table->integer("profession_id")->nullable();

            $table->dateTime('created_at');
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
        //
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProfileRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->enum('type', ["provider", "instructor"]);
            $table->integer('data_id');
            $table->string('link');

            $table->string('image')->nullable();

            $table->string('name')->nullable();
            $table->text('headline')->nullable();
            $table->text('about')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->json('professions')->nullable();

            $table->string('accreditation_number')->nullable();
            $table->dateTime('accreditation_expiration_date')->nullable();
            $table->json('prc_certificate')->nullable();
            $table->string('resume')->nullable();

            $table->dateTime('created_at');
            $table->string('created_by')->default(0);
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->enum('status', ["approved", "blocked", "in-review"])->default("in-review");
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstructorResumes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('instructor_resumes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('image');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('nickname');
            $table->json('pic_identifications');
            $table->json('professions');
            $table->integer('provider_id')->nullable();
            $table->text('residence_address')->nullable();
            $table->text('business_address')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('landline_number')->nullable();
            $table->string('nationality')->nullable();
            $table->json('major_competency_areas')->nullable();
            $table->json('conducted_programs')->nullable();
            $table->json('attended_programs')->nullable();
            $table->json('major_awards')->nullable();
            $table->json('college_background')->nullable();
            $table->json('post_graduate_background')->nullable();
            $table->json('work_experience')->nullable();
            $table->json('aipo_membership')->nullable();
            $table->json('other_affiliations')->nullable();
            $table->enum('status', ['incomplete', 'complete'])->default('incomplete');
            $table->text('notes')->nullable();
            $table->datetime('deleted_at')->nullable();
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
        //
    }
}

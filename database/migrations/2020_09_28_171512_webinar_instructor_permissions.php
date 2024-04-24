<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WebinarInstructorPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_instructor_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('webinar_id');
            $table->integer('webinar_details');
            $table->integer('attract_enrollments');
            $table->integer('instructors');
            $table->integer('video_and_content');
            $table->integer('handouts');
            $table->integer('grading_and_assessment');
            $table->integer('submit_for_accreditation');
            $table->integer('price_and_publish');
            $table->string('created_by')->default(0);
            $table->string('updated_by')->default(0);
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
        Schema::dropIfExists('webinar_instructor_permissions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('provider_id');
            $table->string('url', 250);

            $table->json('profession_id');
            $table->json('instructor_id')->nullable();

            $table->float('price', 8, 2)->nullable();
            
            $table->json('certificate_templates')->nullable();

            $table->integer('course_poster_id')->nullable();
            $table->string('course_poster')->nullable();
            $table->string('course_video')->nullable();
            $table->float('course_video_total_minutes')->nullable();

            $table->text('title');
            $table->longText('headline')->nullable();
            $table->longText('description')->nullable();
            $table->longText('marketing_description')->nullable();
            $table->json('objectives')->nullable();
            $table->json('requirements')->nullable();
            $table->json('target_students')->nullable();
            $table->integer('target_number_students')->nullable();
            
            $table->json('assessment')->nullable(); 
            $table->float('pass_percentage', 3, 3)->nullable();
            
            $table->integer('allow_handouts')->nullable();
            
            $table->json('submit_accreditation_evaluation')->nullable();
            $table->json('accreditation')->nullable();
            $table->json('expenses_breakdown')->nullable();
            $table->string('program_accreditation_no', 500)->nullable();
            $table->string('total_unit_amounts')->nullable();


            $table->string('allow_retry')->nullable();
            $table->string('allow_forward')->nullable();

            $table->dateTime('session_start')->nullable();
            $table->dateTime('session_end')->nullable();

            $table->enum('language', ['english', 'tagalog', 'mixed'])->nullable();
            $table->enum('prc_status', ['draft', 'in-review', 'approved', 'closed']);
            $table->enum('fast_cpd_status', ['draft', 'in-review', 'approved', 'published', 'live', 'cancelled', 'ended']);

            $table->dateTime('created_at');
            $table->string('created_by')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->index(['provider_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            /**
             * Social login
             * 
             */
            $table->string('authID')->nullable();
            $table->string('authToken')->nullable();
            $table->string('authExpiresIn')->nullable();
            $table->enum('authSocial', ["facebook", "google", "linkedin"])->nullable();

            $table->string('username', 250)->nullable();
            $table->integer('un_change')->default(0);
            $table->string('first_name', 500);
            $table->string('middle_name', 500);
            $table->string('last_name', 500);

            $table->string('name', 500);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();

            $table->integer('provider_id')->nullable();
            $table->json('professions')->nullable();
            $table->enum('instructor',[
                'pending', 'active', 'inactive', 'in-review', 'denied', 'none'
            ])->default('none');
            $table->enum('accreditor',[
                'active', 'inactive', 'none'
            ])->default('none');

            $table->enum('superadmin',[
                'active', 'inactive', 'waiting', 'delete','none'
            ])->default('none');

            $table->text('headline')->nullable();
            $table->text('about')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('contact')->nullable();
            $table->string('remember_token')->nullable();
            
            $table->string('created_by')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->dateTime('email_verified_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'delete']);
            $table->index(['username', 'email', 'status', 'email_verified_at', 'provider_id',]);

            $table->json('prc_id')->nullable();
            $table->string('resume')->nullable();
            $table->string('signature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url', 250);
            $table->string('name', 500);
            
            $table->string('logo')->nullable();
            $table->json('profession_id');

            $table->string('email')->unique();
            $table->string('contact')->nullable();

            $table->text('headline')->nullable(); 
            $table->text('about')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('accreditation_number', 500)->nullable();;
            $table->dateTime('accreditation_expiration_date')->nullable();;
            $table->string('prc', 500)->nullable();

            /**
             * Promotions
             * 
             */
            $table->integer('allow_marketing')->default(1);
            $table->integer('allow_affiliate')->default(0);

            $table->enum('status', ['approved', 'blocked', 'in-review']);

            $table->integer('created_by')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->index(['url', 'name', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
}

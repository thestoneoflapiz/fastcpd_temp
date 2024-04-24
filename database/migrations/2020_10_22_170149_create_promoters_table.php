<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promoters', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('username', 250)->nullable();
            $table->integer('un_change')->default(0);
            $table->string('first_name', 500);
            $table->string('middle_name', 500);
            $table->string('last_name', 500);

            $table->string('name', 500);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive', 'delete','invited']);

            $table->string('created_by')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();


            $table->text('headline')->nullable();
            $table->text('about')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('marketing_references')->nullable();
            $table->string('payout_settings')->nullable();
            $table->string('contact')->nullable();
            $table->string('remember_token')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promoters');
    }
}

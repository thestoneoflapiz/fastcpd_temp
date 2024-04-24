<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SuperadminPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('superadmin_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('module_name');
            $table->integer('view');
            $table->integer('create');
            $table->integer('edit');
            $table->integer('delete');

            $table->string('created_by')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'module_name']);
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

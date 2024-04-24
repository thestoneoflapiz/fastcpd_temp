<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('provider_id');
            $table->integer('user_id');
            $table->string('module_name');
            $table->integer('view');
            $table->integer('create');
            $table->integer('edit');
            $table->integer('delete');

            $table->string('created_by')->default(0);
            $table->timestamps();

            $table->index(['provider_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_permissions');
    }
}

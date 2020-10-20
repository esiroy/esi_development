<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserManagerPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('manager_id');
        });

        Schema::table('manager_user', function($table) {
            $table->foreign('user_id', 'user_id_fk_6875')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('manager_id', 'manager_id_fk_6875')->references('id')->on('managers')->onDelete('cascade');
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_user');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTutorPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('tutor_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tutor_id');
        });

        Schema::table('tutor_user', function($table) {
            $table->foreign('user_id', 'user_id_fk_6874')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tutor_id', 'tutor_id_fk_6874')->references('id')->on('tutors')->onDelete('cascade');
        });       
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutor_user');
    }
}

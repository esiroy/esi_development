<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteTutorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_tutor', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');
            $table->unsignedBigInteger('tutor_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('sequence_number')->nullable();            
            $table->timestamps();
        });

        Schema::table('role_user', function($table) {
            $table->foreign('user_id', 'user_id_fk_210518_1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id', 'role_id_fk_210518_2')->references('id')->on('roles')->onDelete('cascade');
        });          
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorite_tutor');
    }
}

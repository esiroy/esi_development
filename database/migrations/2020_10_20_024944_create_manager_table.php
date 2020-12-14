<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');            
            $table->string('name_en');
            $table->string('name_jp');            
            $table->boolean('is_japanese');
            $table->boolean('is_terminated')->nullable();             
            $table->timestamps();
        });

        Schema::table('managers', function($table) {
            $table->foreign('user_id', 'user_id_fk_5558')->references('id')->on('users')->onDelete('cascade');            
        });           
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
}

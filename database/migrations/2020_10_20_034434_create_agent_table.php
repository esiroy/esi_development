<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->string('agent_id')->nullable();
            $table->date('contract_date')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('industry_type')->nullable(); 
            $table->double('management_expense', 15, 8)->nullable();
            $table->double('registration_fee', 15, 8)->nullable();
            $table->longText('remark')->nullable();
            $table->string('representative', 50)->nullable();
            $table->string('hiragana')->nullable();
            $table->string('inclination')->nullable();           
            $table->dateTime('credits_expiration')->nullable();
            
        });

        Schema::table('agents', function($table) {
            $table->foreign('user_id', 'user_id_fk_7558')->references('id')->on('users')->onDelete('cascade');            
        });             
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}

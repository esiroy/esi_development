<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAgentPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('agent_id');
        });

        Schema::table('agent_user', function($table) {
            $table->foreign('user_id', 'user_id_fk_6976')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agent_id', 'agent_id_fk_6976')->references('id')->on('agents')->onDelete('cascade');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_user');
    }
}

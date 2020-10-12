<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_information', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attribute_id');
            $table->bigInteger('agent_id');

            $table->string('nickname');
            $table->enum('gender', ['male', 'female']);
            $table->string('communication_app_name');
            $table->string('communication_app_username');
            $table->date('birthdate');
            $table->integer('age');
            $table->timestamps();
            $table->enum('membership', ['point balance', 'monthly', 'both']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_information');
    }
}

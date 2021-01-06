<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesiredSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desired_schedule', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');            
            $table->string('day')->nullable();
            $table->string('desired_time')->nullable();
            $table->bigInteger('member_id')->nullable();
            $table->Integer('sequence_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('desired_schedule');
    }
}

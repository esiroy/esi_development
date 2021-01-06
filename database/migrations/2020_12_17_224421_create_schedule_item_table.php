<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_item', function (Blueprint $table) {
            $table->id();            
            $table->dateTime('lesson_time')->nullable();                       
            $table->unsignedBigInteger('tutor_id')->nullable();    
            $table->unsignedBigInteger('member_id')->nullable();        
            $table->string('schedule_status', 30)->nullable();
            $table->integer('duration')->nullable();            
            $table->integer('lesson_shift_id')->nullable();
            $table->text('memo')->nullable();
            $table->tinyInteger('valid');
            $table->string('email_type')->nullable();
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
        Schema::dropIfExists('schedule_item');
    }
}

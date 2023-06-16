<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_history', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('parent_lesson_id')->nullable()->index('parent_lesson_id');

            $table->unsignedBigInteger('schedule_id')->index('schedule_id');            
            $table->unsignedBigInteger('member_id')->index('member_id');
            $table->unsignedBigInteger('tutor_id')->index('tutor_id');
            $table->unsignedBigInteger('folder_id')->nullable()->index('folder_id');
            $table->unsignedInteger('total_slides'); 
            $table->unsignedInteger('current_slide'); 

            
            $table->string('status', '12');  //active, skipped, finished, cancelled
            $table->string('additional_notes', '64');  //acti

            //member lesson started
            $table->dateTime('time_started');
            $table->dateTime('time_ended');

            //foreign key references
            $table->foreign('schedule_id')->references('id')->on('schedule_item');                
            $table->foreign('folder_id')->references('id')->on('folders');
            $table->foreign('member_id')->references('id')->on('users');
            $table->foreign('tutor_id')->references('id')->on('users');               
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_history');
    }
}

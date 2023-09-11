<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_survey', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('member_user_id');
            $table->unsignedBigInteger('tutor_user_id');
            $table->longText('feedback')->nullable();
            $table->longText('message')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });

        Schema::table('lesson_survey', function($table) {
            $table->foreign('schedule_id', 'ls_schedule_id_fk_7011')->references('id')->on('schedule_item');            
            $table->foreign('member_user_id', 'ls_member_user_id_fk_7012')->references('id')->on('users');            
            $table->foreign('tutor_user_id', 'ls_tutor_user_id_fk_7013')->references('id')->on('users');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_survey');
    }
}

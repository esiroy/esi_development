<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonSurveyDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_survey_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_survey_id');
            $table->string('name', 32)->fullText('name');
            $table->text('description');
            $table->integer('value');
            $table->smallInteger('order_id');
            $table->boolean('is_active');
            $table->timestamps();
        });   

        Schema::table('lesson_survey_details', function($table) {
            $table->foreign('lesson_survey_id', 'lsd_lesson_survey_id_fk_7014')->references('id')->on('lesson_survey');                        
        });                     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_survey_details');
    }
}

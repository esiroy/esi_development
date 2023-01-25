<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonSlideHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_slide_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_history_id')->index('lesson_history_id'); 
            $table->unsignedBigInteger('slide_index')->index('slide_index');
            $table->longText('content');
            $table->timestamps();
            
            $table->foreign('lesson_history_id')->references('id')->on('lesson_history');       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_slide_history');
    }
}

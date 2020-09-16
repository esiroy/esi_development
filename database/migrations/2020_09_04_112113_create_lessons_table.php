<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {            
            $table->id();
            $table->bigInteger('teacher_id'); //teacher id
            $table->bigInteger('student_id'); //member id            
            $table->bigInteger('lesson_status_id');
            $table->bigInteger('creator_id');
            $table->bigInteger('email_type_id');
            $table->dateTime('scheduled_at');
            $table->integer('duration');            
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
        Schema::dropIfExists('lessons');
    }
}

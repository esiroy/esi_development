<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_card', function (Blueprint $table) {
            $table->id();            
            $table->tinyInteger('valid');
            $table->text('comment')->nullable();
            $table->string('grade', 30);
            $table->unsignedBigInteger('schedule_item_id');    //lesson schedule id
            $table->unsignedBigInteger('course_category_id')->nullable();  //null live/
            $table->unsignedBigInteger('course_item_id')->nullable(); //null on live
            $table->string('lesson_course');
            $table->string('lesson_material');
            $table->string('lesson_subject');
            $table->unsignedBigInteger('member_id')->nullable();
            $table->Integer('lesson_level')->nullabel();            
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
        Schema::dropIfExists('report_card');
    }
}

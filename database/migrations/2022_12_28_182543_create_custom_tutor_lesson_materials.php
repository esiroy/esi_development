<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomTutorLessonMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_tutor_lesson_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_schedule_id');
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->string('file_name');
            $table->string('upload_name');
            $table->string('path');
            $table->integer('size');  
            $table->integer('order_id');          
            $table->timestamps();

           
            $table->index('lesson_schedule_id');
            $table->index('folder_id');
            $table->index('order_id');    
        });
        
        //foreign key references
        Schema::table('custom_tutor_lesson_materials', function($table) {
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('lesson_schedule_id')->references('id')->on('schedule_item')->onDelete('cascade');                       
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_tutor_lesson_materials');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_material', function (Blueprint $table) {
            $table->id();            
            $table->tinyInteger('valid');
            $table->string('filename')->nullable();            
            $table->string('path')->nullable();            
            $table->unsignedBigInteger('course_item_id')->nullable();
            $table->unsignedBigInteger('course_category_id')->nullable();
            $table->Integer('sequence_number')->nullable();      
            $table->Integer('sort_order')->nullable();                                                                        
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
        Schema::dropIfExists('lesson_material');
    }
}

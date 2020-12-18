<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCategoryImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_category_image', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('course_category_id')->nullable();
            $table->string('filename')->nullable();            
            $table->string('path')->nullable();
            $table->tinyInteger('valid');                        
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
        Schema::dropIfExists('course_category_image');
    }
}

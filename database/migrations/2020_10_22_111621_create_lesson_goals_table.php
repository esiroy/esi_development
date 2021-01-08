<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_goals', function (Blueprint $table) {
            $table->id(); 
            $table->tinyInteger('valid');
            $table->string('extra_detail', 255)->nullable(); 
            $table->string('goal', 255)->nullable();
            $table->string('purpose')->nullable();
            $table->string('year_level', 255)->nullable();
            $table->bigInteger('member_id')->nullable();
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
        Schema::dropIfExists('lesson_goals');
    }
}

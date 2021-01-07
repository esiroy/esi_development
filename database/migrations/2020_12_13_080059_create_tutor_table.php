<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->string('fluency', 30);        
            $table->string('gender', 30);
            $table->string('grade', 30);
            $table->longText('hobby')->nullable();
            $table->longText('introduction')->nullable();
            $table->string('major')->nullable();             
            $table->double('salary_rate', 8, 2)->nullable();            
            $table->integer('sort')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->date('birthday')->nullable();
            $table->string('skype_id')->nullable();
            $table->string('skype_name')->nullable();
            $table->string('skype_password', 30)->nullable();
            $table->unsignedBigInteger('lesson_shift_id')->nullable();
            $table->tinyInteger('is_default_main_tutor')->nullable();
            $table->tinyInteger('is_default_support_tutor')->nullable();
            $table->boolean('is_terminated')->nullable();
        });

        Schema::table('tutors', function($table) {
            $table->foreign('lesson_shift_id', 'lesson_shift_id_fk_5556')->references('id')->on('shifts');
            $table->foreign('user_id', 'user_id_fk_5556')->references('id')->on('users');       
        });              
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutors');
    }
}

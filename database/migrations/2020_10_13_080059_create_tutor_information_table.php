<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutorInformationTable extends Migration
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
            $table->unsignedBigInteger('user_id');
            $table->integer('sort');
            $table->integer('salary_rate')->nullable();
            $table->unsignedBigInteger('member_grade_id');            
            $table->string('skype_name');
            $table->string('skype_id');  
            $table->string('name_en');
            $table->string('name_jp');
            $table->enum('gender', ['male', 'female']);
            $table->string('hobby')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('major_in')->nullable();             
            $table->longText('introduction')->nullable();
            $table->string('shift_id');            
            $table->string('japanese_fluency_id');
            $table->boolean('is_default_main_tutor')->nullable();
            $table->boolean('is_terminated')->nullable();            
            $table->timestamps();
        });

        Schema::table('tutors', function($table) {
            $table->foreign('user_id', 'user_id_fk_5556')->references('id')->on('users')->onDelete('cascade');            
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

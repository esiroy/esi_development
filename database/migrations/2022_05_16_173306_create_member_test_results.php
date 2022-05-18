<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberTestResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_test_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_category_id');
            //$table->unsignedBigInteger('question_category_type_id');            
            //$table->unsignedBigInteger('question_id');            
            $table->unsignedBigInteger('user_id'); 

           //member posted date and end
            $table->dateTime('time_started');
            $table->dateTime('time_ended');

            //member result 
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->text('member_answers');           
         
     

            //foreign key references
             $table->foreign('question_category_id')->references('id')->on('question_categories');            
            //$table->foreign('question_category_type_id')->references('id')->on('question_category_type');
            //$table->foreign('question_id')->references('id')->on('questions');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_test_results');
    }
}

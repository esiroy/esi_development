<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleCorrectAnswerToQuestionCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_categories', function (Blueprint $table) {
             $table->boolean('multiple_correct_answers')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_categories', function (Blueprint $table) {
            Schema::dropIfExists('multiple_correct_answers');
        });
    }
}
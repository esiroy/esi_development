<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnaireItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionnaire_id')->nullable();
            $table->string('question', 30);
            $table->string('grade', 30);            
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
        Schema::dropIfExists('questionnaire_item');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportCardMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_card_monthly', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');
            $table->text('comment')->nullable();
            $table->string('grade', 30);
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedBigInteger('tutor_id')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();                        
            $table->unsignedBigInteger('created_by_id')->nullable(); 
            $table->string('grammar_score')->nullable();
            $table->dateTime('listening_score')->nullable();
            $table->string('reading_score')->nullable();
            $table->string('speaking_score')->nullable();
            $table->string('writing_score')->nullable();
            $table->smallInteger('lesson_level')->nullable();            
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
        Schema::dropIfExists('report_card_monthly');
    }
}

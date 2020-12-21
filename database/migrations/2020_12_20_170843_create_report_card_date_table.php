<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportCardDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_card_date', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');
            $table->string('comment')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('grade', 30);
            $table->string('lesson_course');
            $table->dateTime('lesson_date');
            $table->string('lesson_material');
            $table->string('lesson_subject');
            $table->unsignedBigInteger('created_by_id')->nullable();            
            $table->unsignedBigInteger('member_id')->nullable();                
            $table->unsignedBigInteger('tutor_id')->nullable();
            $table->smallInteger('display_tutor_name')->nullable();                        
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
        Schema::dropIfExists('report_card_date');
    }
}

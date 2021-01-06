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
            $table->string('comment', 255)->nullable();
            $table->string('file_name', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->string('grade', 255)->nullable();
            $table->string('lesson_course', 255)->nullable();
            $table->dateTime('lesson_date')->nullable();
            $table->string('lesson_material', 255)->nullable();
            $table->string('lesson_subject', 255)->nullable();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEikenExamRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eiken_exam_record', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');            
            $table->string('eiken_grade')->nullable();
            $table->integer('year')->nullable();
            $table->string('month')->nullable();               
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
        Schema::dropIfExists('eiken_exam_record');
    }
}

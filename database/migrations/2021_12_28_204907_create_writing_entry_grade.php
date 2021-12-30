<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWritingEntryGrade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writing_entry_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('writing_entry_id');
            $table->text('course');
            $table->text('material')->nullable();
            $table->text('subject')->nullable();
            $table->boolean('appointed');
            $table->double('grade', 8, 2);
            $table->integer('words');
            $table->text('content');
            $table->text('attachment')->nullable();
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
        Schema::dropIfExists('writing_entry_grades');
    }
}

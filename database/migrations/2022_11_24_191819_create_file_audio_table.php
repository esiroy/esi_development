<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileAudioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_audio', function (Blueprint $table) {
            $table->id();
            $table->integer('folder_id');
            $table->integer('file_id');
            $table->string('file_name');
            $table->string('upload_name');
            $table->string('path');
            $table->integer('size');
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
        Schema::dropIfExists('file_audio');
    }
}

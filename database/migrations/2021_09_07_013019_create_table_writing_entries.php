<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWritingEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writing_entries', function (Blueprint $table) {
            $table->id();
            $table->string('type', 15)->nullable();
            $table->unsignedBigInteger('form_id')->nullable();              
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->unsignedBigInteger('appointed_tutor_id')->nullable();            
            $table->integer('total_points')->nullable();
            $table->text('value')->nullable();            
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
        Schema::dropIfExists('writing_entries');
    }
}

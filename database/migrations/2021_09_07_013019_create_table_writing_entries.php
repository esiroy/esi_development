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
            $table->unsignedBigInteger('form_id');          
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('appointed_tutor_id')->nullable();
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
        Schema::dropIfExists('table_writing_entries');
    }
}

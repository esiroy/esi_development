<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_notes', function (Blueprint $table) {
            $table->id();          
            $table->unsignedBigInteger('tutor_id')->nullable();
            $table->unsignedBigInteger('member_id');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('member_notes');
    }
}

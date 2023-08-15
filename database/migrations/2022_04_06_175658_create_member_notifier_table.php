<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberNotifierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_notifier', function (Blueprint $table) {
            $table->id(); 
            $table->date('date');
            $table->unsignedBigInteger('member_id');
            $table->string("type", 16)->fullText('type');

            $table->foreign('member_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_notifier');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoReplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memo_replies', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('schedule_item_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->string("message_type", 10);
            $table->text('message')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::table('memo_replies', function($table) {       
            $table->foreign('schedule_item_id', 'memo_sender_id_fk_1001')->references('id')->on('schedule_item');     
            $table->foreign('sender_id', 'memo_sender_id_fk_1002')->references('id')->on('users');
            $table->foreign('recipient_id', 'memo_recipient_id_fk_1003')->references('id')->on('users');
            //add index
            $table->index(['schedule_item_id', 'sender_id', 'recipient_id']);
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memo_replies');
    }
}

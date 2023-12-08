<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonChatHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_chat_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');              
            $table->text('message')->nullable();
            $table->string("message_type", 12);
            $table->boolean('is_read')->default(false);
            $table->tinyInteger('valid');            
            $table->timestamps();
        });

        Schema::table('lesson_chat_history', function($table) {                   
            $table->foreign('sender_id', 'lesson_chat_history_sender_id_fk_1001')->references('id')->on('users');
            $table->foreign('recipient_id', 'lesson_chat_history_recipient_id_fk_1002')->references('id')->on('users');                    
            $table->index(['lesson_id','sender_id', 'recipient_id']);
        });          
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_chat_history', function (Blueprint $table) {
             Schema::dropIfExists('lesson_chat_history');
        });
    }
}

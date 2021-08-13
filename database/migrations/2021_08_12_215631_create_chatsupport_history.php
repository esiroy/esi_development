<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsupportHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatsupport_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');              
            $table->text('message')->nullable();
            $table->string("message_type", 12);
            $table->boolean('is_read')->default(false);
            $table->tinyInteger('valid');            
            $table->timestamps();
        });

        Schema::table('chatsupport_history', function($table) {                   
            $table->foreign('sender_id', 'chatsupport_historysender_id_fk_1001')->references('id')->on('users');
            $table->foreign('recipient_id', 'chatsupport_historyrecipient_id_fk_1002')->references('id')->on('users');            
            $table->index(['sender_id', 'recipient_id']);
        });          
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatsupport_history');
    }
}

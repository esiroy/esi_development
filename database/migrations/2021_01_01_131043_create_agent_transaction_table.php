<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_transaction', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');
            $table->double('amount', 8, 2)->nullable();
            $table->longText('remarks')->nullable();                   
            $table->string('transaction_type', 30);
            $table->unsignedBigInteger('agent_id')->nullable();            
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();   
            $table->unsignedBigInteger('schedule_item_id')->nullable();               
            $table->double('price', 8, 2)->nullable();
            $table->unsignedBigInteger('lesson_shift_id')->nullable();     
            $table->dateTime('credits_expiration')->nullable();
            $table->dateTime('old_credits_expiration')->nullable();
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
        Schema::dropIfExists('agent_transaction');
    }
}

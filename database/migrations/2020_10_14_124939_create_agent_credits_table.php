<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_credits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_id');
            //Transacted by
            $table->bigInteger('transaction_user_id');
            $table->string('transaction_type');

            //Payment
            $table->double('amount')->nullable();
            $table->integer('credits')->nullable();
            $table->text('remarks')->default('')->nullable(true);
           
            //date
            $table->dateTime('original_credit_expiration_date', 0)->nullable();            
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
        Schema::dropIfExists('agent_credits');
    }
}

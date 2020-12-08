<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_credits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');

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
        Schema::dropIfExists('member_credits');
    }
}

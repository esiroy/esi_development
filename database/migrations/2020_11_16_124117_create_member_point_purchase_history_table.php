<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberPointPurchaseHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_point_purchase_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');

            //Transacted by
            $table->bigInteger('transaction_user_id');
                        
            $table->double('amount');
            $table->integer('credits');
            $table->integer('lesson_time_duration')->nullable();           
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
        Schema::dropIfExists('member_point_purchase_history');
    }
}

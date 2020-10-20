<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');            
            $table->integer('industry_type_id');         
            $table->string('name_en');
            $table->string('name_jp');
            $table->string('representative');
            $table->string('hiragana')->nullable();
            $table->string('address')->nullable();            
            $table->string('inclination')->nullable();
            $table->date('contract_date')->nullable();
            $table->longText('agent_remark')->nullable();
            $table->boolean('is_terminated')->nullable();            
            //points
            $table->date('initial_date_of_purchase')->nullable();
            $table->integer('point_balance')->nullable();
            $table->date('expire_date')->nullable();
            $table->decimal('purchased_amount', 8, 2)->nullable();
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
        Schema::dropIfExists('agents');
    }
}

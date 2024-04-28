<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberMultiAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_multi_account', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->fullText('name');
            $table->text('description')->nullable();           
            $table->tinyInteger('sequence_number')->nullable();
            $table->tinyInteger('is_default');          
            $table->tinyInteger('valid'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_multi_account');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberMultiAccountAliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_multi_account_alias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_multi_account_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name', 100)->fullText('name');
            $table->text('description')->nullable();           
            $table->tinyInteger('sequence_number')->nullable();
            $table->tinyInteger('selected'); 
            $table->tinyInteger('is_default');          
            $table->tinyInteger('valid');             
            $table->timestamps();


            //foreign key references
            $table->foreign('member_multi_account_id')->references('id')->on('member_multi_account');  
            $table->foreign('user_id')->references('id')->on('users');                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_multi_account_alias');
    }
}

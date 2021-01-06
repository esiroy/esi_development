<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_attribute', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');
            $table->string('attribute')->nullable();
            $table->integer('lesson_limit')->nullable();
            $table->string('month')->nullable();            
            $table->integer('year')->nullable();                        
            $table->bigInteger('member_id')->nullable();            
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
        Schema::dropIfExists('member_attribute');
    }
}

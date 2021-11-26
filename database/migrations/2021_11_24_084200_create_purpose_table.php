<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurposeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_purpose', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');   
            $table->string('purpose')->nullable();
            $table->text('purpose_options')->nullable();
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
        Schema::dropIfExists('purpose');
    }
}

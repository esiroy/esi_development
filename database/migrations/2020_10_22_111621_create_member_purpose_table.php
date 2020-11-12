<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberPurposeTable extends Migration
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
            $table->bigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('purpose')->nullable();
            $table->string('purpose_level')->nullable();
            $table->string('other')->nullable();            
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
        Schema::dropIfExists('member_purpose');
    }
}

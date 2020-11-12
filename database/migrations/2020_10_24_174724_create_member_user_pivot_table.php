<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('member_id');
        });

        Schema::table('member_user', function($table) {
            $table->foreign('user_id', 'user_id_fk_8874')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('member_id', 'member_id_fk_8874')->references('id')->on('members')->onDelete('cascade');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_user');
    }
}

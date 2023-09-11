<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFolderUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('folder_user')) {
            Schema::create('folder_user', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('folder_id');
            });

            Schema::table('folder_user', function($table) {
                $table->foreign('user_id', 'user_id_fk_6885')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('folder_id', 'folder_id_fk_6885')->references('id')->on('folders')->onDelete('cascade');
            });
        };

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folder_user');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->bigInteger('parent_id')->default(0)->after('id');; //zero is the parent
            $table->bigInteger('user_id')->after('parent_id');
            $table->bigInteger('order_id')->after('user_id');
            $table->enum('privacy', ['public', 'private'])->default('private')->after('folder_description');
            $table->text('slug')->after('order_id');

            //added thumbnails
            $table->string('thumb_file_name');
            $table->string('thumb_upload_name');
            $table->string('thumb_path');
            $table->integer('thumb_size');              
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('slug');
        });
    }
}

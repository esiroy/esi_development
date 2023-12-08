<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFolderThumbs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            //added thumbnails
            if (!Schema::hasColumn('folders', 'thumb_file_name')) {
                $table->string('thumb_file_name');
            }

            if (!Schema::hasColumn('folders', 'thumb_upload_name')) {
                $table->string('thumb_upload_name');
            }

            if (!Schema::hasColumn('folders', 'thumb_path')) {
                $table->string('thumb_path');
            }

            if (!Schema::hasColumn('folders', 'thumb_size')) {
                $table->integer('thumb_size');
            }  
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
            // Drop the columns only if they exist
            if (Schema::hasColumn('folders', 'thumb_file_name')) {
                $table->dropColumn('thumb_file_name');
            }

            if (Schema::hasColumn('folders', 'thumb_upload_name')) {
                $table->dropColumn('thumb_upload_name');
            }

            if (Schema::hasColumn('folders', 'thumb_path')) {
                $table->dropColumn('thumb_path');
            }

            if (Schema::hasColumn('folders', 'thumb_size')) {
                $table->dropColumn('thumb_size');
            }           
        });
    }
}

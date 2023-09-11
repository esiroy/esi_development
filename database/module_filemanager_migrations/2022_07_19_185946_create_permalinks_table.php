<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermalinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permalinks', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->text('permalink');
        });

        Schema::table('permalinks', function($table) {
            $table->foreign('id', 'permalink_id_fk_6883')->references('id')->on('folders')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permalinks');
    }
}

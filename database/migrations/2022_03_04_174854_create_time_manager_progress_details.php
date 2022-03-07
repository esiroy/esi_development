<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeManagerProgressDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_manager_progress_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_manager_id')->index('time_manager_id');   
            $table->unsignedBigInteger('member_id');
            $table->double('total_minutes', 18, 4);
            $table->text('minutes');
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
        Schema::dropIfExists('time_manager_progress_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLessonSlideHistoryBatchNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_slide_history', function (Blueprint $table) {
            // Check if the 'batch' column doesn't exist, then add it
            if (!Schema::hasColumn('lesson_slide_history', 'batch')) {
                $table->integer('batch')->default(1);
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
        Schema::table('lesson_slide_history', function (Blueprint $table) {
            // Remove the batch column in the down method
            if (Schema::hasColumn('lesson_slide_history', 'batch')) {            
                $table->dropColumn('batch');
            }
        });
    }
}

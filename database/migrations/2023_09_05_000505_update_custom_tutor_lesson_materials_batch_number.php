<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomTutorLessonMaterialsBatchNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_tutor_lesson_materials', function (Blueprint $table) {
            // Check if the 'batch' column doesn't exist, then add it
            if (!Schema::hasColumn(' custom_tutor_lesson_materials', 'batch')) {
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
        Schema::table('custom_tutor_lesson_materials', function (Blueprint $table) {
            // Remove the batch column in the down method
            if (Schema::hasColumn(' custom_tutor_lesson_materials', 'batch')) {            
                $table->dropColumn('batch');
            }
        });
    }
}

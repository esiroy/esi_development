<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLessonSlideHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('lesson_slide_history', 'data')) {
            Schema::table('lesson_slide_history', function (Blueprint $table) {
                $table->longText('data')->after('content'); // Add "data" column
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('lesson_slide_history', 'data')) {
            Schema::table('lesson_slide_history', function (Blueprint $table) {
                $table->dropColumn('data');
            });
        }
    }
}

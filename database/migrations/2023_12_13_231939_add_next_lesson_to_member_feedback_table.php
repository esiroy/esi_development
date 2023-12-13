<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNextLessonToMemberFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_feedback', function (Blueprint $table) {
            $table->integer('next_lesson')->nullable()->after('tutor_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_feedback', function (Blueprint $table) {
            $table->dropColumn('next_lesson');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemberMultiAccountIdToScheduleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_item', function (Blueprint $table) {
            $table->unsignedBigInteger('member_multi_account_id')->nullable()->after('lesson_time');
            $table->index('member_multi_account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_item', function (Blueprint $table) {
            $table->dropColumn('member_multi_account_id');
        });
    }
}

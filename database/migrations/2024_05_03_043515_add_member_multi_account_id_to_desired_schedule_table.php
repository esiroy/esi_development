<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemberMultiAccountIdToDesiredScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('desired_schedule', function (Blueprint $table) {
            $table->unsignedBigInteger('member_multi_account_id')->nullable()->after('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('desired_schedule', function (Blueprint $table) {
            $table->dropColumn('member_multi_account_id');
        });
    }
}

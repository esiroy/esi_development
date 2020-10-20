<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_information', function (Blueprint $table) {
            //personal info
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('member_attribute_id');
            $table->bigInteger('agent_id');
            $table->enum('gender', ['male', 'female']);
            $table->date('birthdate');
            $table->integer('age');
            $table->string('communication_app_name');
            $table->string('communication_app_username');            
            $table->bigInteger('membership_id');

            //purpose
            $table->bigInteger('preferred_tutor_id');
            $table->bigInteger('member_lesson_classes_id');

            //lesson details
            $table->date('member_since');
            $table->bigInteger('lesson_time_id');
            $table->bigInteger('main_tutor_id');

            //exam record
            $table->bigInteger('exam_record_id');

            //report card
            $table->boolean('member_report_card');
            $table->boolean('agent_report_card');
            $table->boolean('member_monthly_report');
            $table->boolean('agent_monthly_report');

            //point purchase
            $table->enum('Direct', ['Agent', 'Direct']);

            //Desired Schedule
            $table->date('descired_schedules');

            //timestamp of transaction
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
        Schema::dropIfExists('member_information');
    }
}

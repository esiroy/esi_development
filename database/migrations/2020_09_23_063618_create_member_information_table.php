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
        Schema::create('members', function (Blueprint $table) {
            //personal info
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('member_attribute_id')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->string('nickname');
            
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('age')->nullable();

            $table->string('communication_app_name');
            $table->string('communication_app_username');            
            $table->bigInteger('membership_id')->nullable();

            //lesson details
            $table->date('member_since')->nullable();
            $table->bigInteger('lesson_time_id')->nullable();
            $table->bigInteger('main_tutor_id')->nullable();

            //exam record
            $table->bigInteger('exam_record_id')->nullable();

            //report card
            $table->boolean('member_report_card')->nullable();
            $table->boolean('agent_report_card')->nullable();
            $table->boolean('member_monthly_report')->nullable();
            $table->boolean('agent_monthly_report')->nullable();

            //point purchase
            $table->enum('point_purchase', ['Agent', 'Direct'])->nullable();

            //sumarry of points
            $table->bigInteger('credits')->nullable();
            $table->dateTime('credits_expiration')->nullable();
            $table->dateTime('latest_purchase_date')->nullable();
            
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
        Schema::dropIfExists('members');
    }
}

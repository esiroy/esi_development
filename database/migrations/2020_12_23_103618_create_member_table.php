<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberTable extends Migration
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
            $table->unsignedBigInteger('user_id');
           
            //basic member preference info
            $table->text('hobby')->nullable();
            $table->string('level')->nullable();
            $table->string('preferred_tutor_character', 30)->nullable();
            $table->string('preferred_tutor_experience', 30)->nullable();
            $table->string('preferred_gender', 30)->nullable();
            $table->string('purpose')->nullable();
            $table->string('student_year')->nullable();
            $table->string('preferred_support_type')->nullable();
            $table->string('year')->nullable();

            //member attributes
            $table->unsignedBigInteger('tutor_id')->nullable();
            $table->integer('age')->nullable();
            $table->string('attribute')->nullable();            
            $table->dateTime('birthday')->nullable();
            $table->date('member_since')->nullable(); 
            $table->string('nickname');    
            $table->string('gender', 30);
            
            $table->unsignedBigInteger('agent_id')->nullable();            
            $table->unsignedBigInteger('lesson_shift_id')->nullable();
            $table->bigInteger('course_category_id')->nullable();
            $table->bigInteger('course_item_id')->nullable();
            $table->string('english_level', 30)->nullable();
            
            $table->boolean('is_monthly_report_card_visible')->nullable();
            $table->boolean('is_monthly_report_card_visible_to_agent')->nullable();
            $table->boolean('is_report_card_visible')->nullable();
            $table->boolean('is_report_card_visible_to_agent')->nullable();
                        
         
            //sumarry of points
            $table->dateTime('credits_expiration')->nullable();
            $table->string('membership', 30)->nullable();

            $table->string('communication_app', 30)->nullable();
            $table->string('skype_account')->nullable();
            $table->string('zoom_account')->nullable();
            $table->string('point_purchase_type', 30)->nullable();
            $table->integer('no_of_active_reserve')->nullable();
            $table->integer('no_of_active_reserve_left')->nullable();
            
            //timestamp of transaction
            $table->timestamps();
        });


        Schema::table('members', function($table) {
            $table->foreign('user_id', 'user_id_fk1_0001')->references('id')->on('users');                        
            $table->foreign('tutor_id', 'tutor_id_fk1_00001')->references('user_id')->on('tutors')->onDelete('cascade');             
            $table->foreign('lesson_shift_id', 'lesson_shift_id_fk1_0001')->references('id')->on('shifts');            
            $table->foreign('agent_id', 'agent_id_fk1_0001')->references('user_id')->on('agents');            
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


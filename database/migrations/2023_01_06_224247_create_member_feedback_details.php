<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberFeedbackDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_feedback_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_feedback_id');
            $table->string('name', 32)->fullText('name');
            $table->text('description');
            $table->integer('value');
            $table->smallInteger('order_id');
            $table->boolean('is_active');
            $table->timestamps();
        });

        Schema::table('member_feedback_details', function($table) {
            $table->foreign('member_feedback_id', 'mfd_member_feedback_id_fk_7025')->references('id')->on('member_feedback');                        
        });   
                    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_feedback_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_manager', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');
            $table->unsignedBigInteger('member_id')->index('member_id');            
            $table->string("course", 100)->fullText('course');
            $table->string("grade_level", 100)->fullText('level');
            $table->date('start_date')->index('start_date');
            $table->date('end_date')->index('end_date');
            $table->double('current_score', 18, 4);
            $table->double('target_score', 18, 4);
            $table->double('required_hours', 18, 4);
            $table->boolean('has_materials');
            $table->text('materials');           
            //auto calculated based required_hours minus the entires
            $table->double('time_achievement', 18, 4);              
            $table->double('required_days', 18, 4);  
            $table->double('remaining_days', 18, 4);            
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
        Schema::dropIfExists('time_manager');
    }
}

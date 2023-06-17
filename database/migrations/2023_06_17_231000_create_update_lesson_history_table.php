<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateLessonHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     
        Schema::table('lesson_history', function (Blueprint $table) {

  

            $table->unsignedBigInteger('parent_lesson_id')->nullable()->index('parent_lesson_id')->after('id');
            $table->foreign('parent_lesson_id')->references('id')->on('lesson_history');                       
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_history', function (Blueprint $table) {
          
            // Drop foreign key constraint
            $table->dropForeign('lesson_history_parent_lesson_id_foreign');

            // Drop the column
            $table->dropColumn('parent_lesson_id');            
        });
    }
}

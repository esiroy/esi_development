<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryTypeIdToQuestionCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('question_category_type_id')->after('id')->index('id')->nullable();

             //$table->foreign('question_category_type_id')->references('id')->on('question_category_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_categories', function (Blueprint $table) {
             $table->dropColumn('question_category_type_id');
        });
    }
}

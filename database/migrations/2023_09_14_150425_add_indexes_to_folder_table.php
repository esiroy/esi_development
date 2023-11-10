<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIndexesToFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {           
            $table->index('parent_id', 'folders_parent_id_index');        
            $table->index('order_id', 'folders_order_id_index');        
            $table->index('user_id', 'folders_user_id_index');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            // Drop the indexes if needed
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['order_id']);
            $table->dropIndex(['user_id']);
        });
    }
}

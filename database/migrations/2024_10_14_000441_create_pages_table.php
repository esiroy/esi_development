<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Page title
            $table->string('slug')->unique(); // URL-friendly identifier
            $table->text('content'); // Main content of the page
            $table->string('meta_title')->nullable(); // SEO meta title
            $table->string('meta_description')->nullable(); // SEO meta description
            $table->boolean('is_netenglish')->default(false); // Publication status
            $table->boolean('is_mytutor')->default(false); // Publication status
            $table->boolean('is_published')->default(false); // Publication status
            $table->timestamp('published_at')->nullable(); // Publication date and time
            $table->timestamps(); // created_at and updated_at columns
            $table->softDeletes(); // deleted_at column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}

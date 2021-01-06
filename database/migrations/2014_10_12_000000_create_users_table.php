<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('valid');

            $table->string('activation_code', 50)->nullable();
            //$table->string('email')->unique();
            //$table->string('username')->unique();
            $table->string('email');
            $table->string('username');            
            $table->string('password');

            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('japanese_lastname')->nullable();
            $table->string('japanese_firstname')->nullable();

            $table->string('user_type', 50);
            $table->tinyInteger('is_activated')->nullable();
            $table->dateTime('last_login')->nullable();

            $table->bigInteger('company_id')->nullable();
            $table->tinyInteger('email_notification')->nullable();
            

            $table->integer('items_per_page')->nullable();
            $table->integer('no_page_item')->nullable();            

            $table->timestamp('email_verified_at')->nullable();
            $table->string('reset_password_code')->nullable();
            $table->tinyInteger('is_japanese')->nullable();


            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

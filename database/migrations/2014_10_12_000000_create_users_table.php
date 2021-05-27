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
            $table->increments('id');
            $table->string('name', 180);
            $table->string('last_name', 180)->nullable();
            $table->string('email', 180)->unique();
            $table->string('shop_name', 180)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 180)->nullable();
            $table->integer('interest_id')->nullable()->default(0);
            $table->integer('country_id')->unsigned()->nullable();
            $table->string('password', 180)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }

}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country', 100);
            $table->string('street_home_no', 500);
            $table->string('apartment_suite_address', 500);
            $table->string('place', 100);
            $table->string('zip', 45);
            $table->tinyInteger('type');
            $table->timestamps();

            $table->integer('user_id')->unsigned()->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}

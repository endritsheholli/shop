<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preorder_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->timestamps();

            $table->foreign('preorder_id')
            ->references('id')
            ->on('preorders')
            ->onDelete('cascade');

            $table->foreign('product_id')
            ->references('id')
            ->on('products')
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
        Schema::dropIfExists('preorder_details');
    }
}

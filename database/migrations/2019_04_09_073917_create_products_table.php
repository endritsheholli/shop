<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('brand_id')->nullable();
            $table->integer('category_id')->unsigned();
            $table->string('title', 180);
            $table->string('description', 512);
            $table->longText('colors')->nullable();
            $table->integer('size')->nullable();
            $table->integer('price')->nullable();
            $table->integer('previous_price')->nullable();
            $table->longText('image_urls')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

           /* $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('cascade');*/

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('products');
    }
}

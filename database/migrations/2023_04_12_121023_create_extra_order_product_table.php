<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extra_id')->references('id')->on('extras')->onDelete('cascade');
            $table->foreignId('order_product_id')->references('id')->on('order_product')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
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
        Schema::dropIfExists('extra_order_product');
    }
};

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
        Schema::create('company_type_fulfillment_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_type_id');
            $table->unsignedBigInteger('fulfillment_type_id');

            $table->foreign('company_type_id')->references('id')->on('company_types')->onDelete('cascade');
            $table->foreign('fulfillment_type_id')->references('id')->on('fulfillment_types')->onDelete('cascade');

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
        Schema::dropIfExists('company_type_fulfillment_type');
    }
};

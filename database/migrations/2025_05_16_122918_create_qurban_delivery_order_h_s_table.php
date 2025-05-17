<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qurban_delivery_order_h', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->string('transaction_number');
            $table->date('transaction_date');
            $table->unsignedBigInteger('qurban_customer_address_id');
            $table->unsignedBigInteger('qurban_sale_livestock_h_id');

            $table->foreign('qurban_customer_address_id')->references('id')->on('qurban_customer_addresses')->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('qurban_sale_livestock_h_id')->references('id')->on('qurban_sale_livestock_h')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_delivery_order_h');
    }
};

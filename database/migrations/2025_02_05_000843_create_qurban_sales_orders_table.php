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
        Schema::dropIfExists('qurban_sales_orders');

        Schema::create('qurban_sales_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qurban_customer_id');
            $table->unsignedBigInteger('farm_id');
            $table->date('order_date');
            $table->float('total_weight');
            $table->integer('quantity');
            $table->longText('description')->nullable();
            $table->enum('status', ['processing', 'completed', 'cancelled'])->default('processing');
            $table->foreign('qurban_customer_id')->references('id')->on('qurban_customers')->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_sales_orders');
    }
};

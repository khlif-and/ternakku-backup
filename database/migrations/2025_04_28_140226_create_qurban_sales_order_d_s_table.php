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
        Schema::create('qurban_sales_order_d', function (Blueprint $table) {
            $table->id();
            $table->float('total_weight');
            $table->unsignedBigInteger('livestock_type_id');
            $table->unsignedBigInteger('qurban_sales_order_id');
            $table->integer('quantity');
            $table->foreign('livestock_type_id')->references('id')->on('livestock_types')->onDelete('cascade');
            $table->foreign('qurban_sales_order_id')->references('id')->on('qurban_sales_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_sales_order_d');
    }
};

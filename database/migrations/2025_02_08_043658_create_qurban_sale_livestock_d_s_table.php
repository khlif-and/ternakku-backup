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
        Schema::create('qurban_sale_livestock_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qurban_sale_livestock_h_id');
            $table->unsignedBigInteger('qurban_customer_address_id');
            $table->unsignedBigInteger('livestock_id');
            $table->float('min_weight');
            $table->float('max_weight');
            $table->float('price_per_kg');
            $table->float('price_per_head');
            $table->foreign('qurban_customer_address_id')->references('id')->on('qurban_customer_addresses')->onDelete('cascade');
            $table->foreign('qurban_sale_livestock_h_id')->references('id')->on('qurban_sale_livestock_h')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_sale_livestock_d');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qurban_delivery_instruction_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qurban_delivery_instruction_h_id');
            $table->unsignedBigInteger('qurban_delivery_order_h_id');

            $table->foreign('qurban_delivery_instruction_h_id', 'delivery_instruction_h')->references('id')->on('qurban_delivery_instruction_h')->onDelete('cascade');
            $table->foreign('qurban_delivery_order_h_id')->references('id')->on('qurban_delivery_order_h')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_delivery_instruction_d');
    }
};

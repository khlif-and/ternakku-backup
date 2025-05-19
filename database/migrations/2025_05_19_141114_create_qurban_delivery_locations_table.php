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
        Schema::create('qurban_delivery_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qurban_delivery_instruction_h_id');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);        
            $table->foreign('qurban_delivery_instruction_h_id', 'q_delivery_instruction_h')->references('id')->on('qurban_delivery_instruction_h')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_delivery_locations');
    }
};

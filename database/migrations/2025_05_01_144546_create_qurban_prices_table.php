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
        Schema::create('qurban_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->integer('hijri_year');
            $table->foreignId('livestock_type_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('start_weight', 8, 2);    // e.g., 20.50 kg
            $table->decimal('end_weight', 8, 2);      // e.g., 30.75 kg
            $table->decimal('price_per_kg', 10, 2);   // e.g., 85000.00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_prices');
    }
};

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
        Schema::create('milk_production_global', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->string('transaction_number');
            $table->date('transaction_date');
            $table->enum('milking_shift', ['morning', 'afternoon']); // Enum for morning or afternoon shift
            $table->time('milking_time'); // Time of milking
            $table->string('milker_name'); // Name of the milker
            $table->float('quantity_liters', 8, 2); // Milk quantity in liters
            $table->string('milk_condition')->nullable(); // Condition of the milk
            $table->string('notes')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');

            // Add unique constraint for transaction_number and farm_id
            $table->unique(['farm_id', 'transaction_number']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_production_global');
    }
};

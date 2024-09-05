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
        Schema::create('feed_medicine_purchase_h', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->string('transaction_number');
            $table->date('transaction_date');
            $table->string('supplier');
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
        Schema::dropIfExists('feed_medicine_purchase_h');
    }
};

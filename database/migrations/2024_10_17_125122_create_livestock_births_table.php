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
        Schema::create('livestock_birth', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('reproduction_cycle_id');

            $table->string('transaction_number');
            $table->date('transaction_date');
            $table->string('officer_name')->nullable();
            $table->float('cost');
            $table->enum('status', ['NORMAL', 'ABORTUS' , 'PREMATURE']);
            $table->date('estimated_weaning')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');

            // Add unique constraint for transaction_number and farm_id
            $table->unique(['farm_id', 'transaction_number']);
            $table->foreign('reproduction_cycle_id')->references('id')->on('reproduction_cycles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_birth');
    }
};

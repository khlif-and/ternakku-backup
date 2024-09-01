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
        Schema::create('milk_analysis_global', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->string('transaction_number');
            $table->date('transaction_date');
            $table->float('fat')->nullable();
            $table->float('snf')->nullable();
            $table->float('density')->nullable();
            $table->float('lactose')->nullable();
            $table->float('salts')->nullable();
            $table->float('protein')->nullable();
            $table->float('a_water')->nullable();
            $table->float('t_sample')->nullable();
            $table->float('f_point')->nullable();
            $table->float('bj')->nullable();
            $table->boolean('at')->nullable();
            $table->float('mbrt')->nullable();
            $table->float('ts')->nullable();
            $table->float('tvc')->nullable();
            $table->boolean('abk')->nullable();
            $table->float('rzn')->nullable();
            $table->boolean('ab')->nullable();
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
        Schema::dropIfExists('milk_analysis_global');
    }
};

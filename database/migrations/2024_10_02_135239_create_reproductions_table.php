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
        Schema::create('reproduction_cycles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_id');
            $table->unsignedBigInteger('reproduction_cycle_status_id');
            $table->enum('insemination_type', ['artificial', 'natural']);
            $table->timestamps();
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
            $table->foreign('reproduction_cycle_status_id')->references('id')->on('reproduction_cycle_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reproduction_cycles');
    }
};

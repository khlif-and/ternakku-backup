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
        Schema::create('livestocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_reception_d_id');
            $table->unsignedBigInteger('livestock_status_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('livestock_reception_d_id')->references('id')->on('livestock_reception_d')->onDelete('cascade');
            $table->foreign('livestock_status_id')->references('id')->on('livestock_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestocks');
    }
};

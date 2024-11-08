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
        Schema::create('milk_analysis_colony_livestocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('milk_analysis_colony_id');
            $table->unsignedBigInteger('livestock_id');
            $table->timestamps();

            $table->foreign('milk_analysis_colony_id')->references('id')->on('milk_analysis_colony')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_analysis_colony_livestocks');
    }
};

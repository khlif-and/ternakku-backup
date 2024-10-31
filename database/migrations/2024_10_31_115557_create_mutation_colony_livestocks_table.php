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
        Schema::create('mutation_colony_livestock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mutation_colony_d_id');
            $table->unsignedBigInteger('livestock_id');
            $table->timestamps();

            $table->foreign('mutation_colony_d_id')->references('id')->on('mutation_colony_d')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutation_colony_livestock');
    }
};

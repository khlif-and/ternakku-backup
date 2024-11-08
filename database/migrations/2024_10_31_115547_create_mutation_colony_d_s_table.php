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
        Schema::create('mutation_colony_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mutation_h_id');
            $table->unsignedBigInteger('pen_id');
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            // Additional fields
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('mutation_h_id')->references('id')->on('mutation_h')->onDelete('cascade');
            $table->foreign('pen_id')->references('id')->on('pens')->onDelete('cascade');
            $table->foreign('from')->references('id')->on('pens')->onDelete('cascade');
            $table->foreign('to')->references('id')->on('pens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutation_colony_d');
    }
};

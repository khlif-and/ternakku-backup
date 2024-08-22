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
        Schema::create('treatment_colony_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_h_id');
            $table->unsignedBigInteger('pen_id');
            $table->string('diagnosis');
            $table->integer('total_livestock');
            $table->float('total_cost');
            $table->float('average_cost');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('treatment_h_id')->references('id')->on('treatment_h')->onDelete('cascade');
            $table->foreign('pen_id')->references('id')->on('pens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_colony_d');
    }
};

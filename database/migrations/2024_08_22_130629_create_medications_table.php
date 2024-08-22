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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_individu_d_id')->nullable();
            $table->unsignedBigInteger('treatment_colony_d_id')->nullable();
            $table->string('medication_name');
            $table->float('quantity');
            $table->string('unit');
            $table->float('cost');
            $table->timestamps();

            $table->foreign('treatment_individu_d_id')->references('id')->on('treatment_individu_d')->onDelete('cascade');
            $table->foreign('treatment_colony_d_id')->references('id')->on('treatment_colony_d')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};

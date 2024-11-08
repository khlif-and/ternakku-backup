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
        Schema::dropIfExists('feeding_colony_d_livestocks');

        Schema::create('feeding_colony_livestock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feeding_colony_d_id');
            $table->unsignedBigInteger('livestock_id');
            $table->timestamps();

            $table->foreign('feeding_colony_d_id')->references('id')->on('feeding_colony_d')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_colony_livestock');

        Schema::create('feeding_colony_d_livestocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feeding_colony_d_id');
            $table->unsignedBigInteger('livestock_id');
            $table->timestamps();

            $table->foreign('feeding_colony_d_id')->references('id')->on('feeding_colony_d')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
        });
    }
};

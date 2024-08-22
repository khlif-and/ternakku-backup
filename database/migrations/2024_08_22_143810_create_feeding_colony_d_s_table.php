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
        Schema::create('feeding_colony_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feeding_h_id');
            $table->unsignedBigInteger('pen_id');

            // Forage details
            $table->string('forage_name');
            $table->float('forage_qty_kg');
            $table->float('forage_price_kg');
            $table->float('forage_total');

            // Concentrate details
            $table->string('concentrate_name');
            $table->float('concentrate_qty_kg');
            $table->float('concentrate_price_kg');
            $table->float('concentrate_total');

            // Feed ingredient details
            $table->string('ingredient_name');
            $table->float('ingredient_qty_kg');
            $table->float('ingredient_price_kg');
            $table->float('ingredient_total');

            $table->integer('total_livestock');
            $table->float('total_cost');
            $table->float('average_cost');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('feeding_h_id')->references('id')->on('feeding_h')->onDelete('cascade');
            $table->foreign('pen_id')->references('id')->on('pens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_colony_d');
    }
};

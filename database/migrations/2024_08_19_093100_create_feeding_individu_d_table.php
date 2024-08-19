<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedingIndividuDTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('feeding_individu_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feeding_h_id');
            $table->unsignedBigInteger('livestock_id');

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

            // Additional fields
            $table->float('total_cost');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('feeding_h_id')->references('id')->on('feeding_h')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_individu_d');
    }
}

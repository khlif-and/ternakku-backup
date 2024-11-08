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
        Schema::table('feeding_colony_d', function (Blueprint $table) {
            $table->dropColumn([
                'forage_name',
                'forage_qty_kg',
                'forage_price_kg',
                'forage_total',
                'concentrate_name',
                'concentrate_qty_kg',
                'concentrate_price_kg',
                'concentrate_total',
                'ingredient_name',
                'ingredient_qty_kg',
                'ingredient_price_kg',
                'ingredient_total',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feeding_colony_d', function (Blueprint $table) {
            $table->string('forage_name');
            $table->float('forage_qty_kg');
            $table->float('forage_price_kg');
            $table->float('forage_total');

            $table->string('concentrate_name');
            $table->float('concentrate_qty_kg');
            $table->float('concentrate_price_kg');
            $table->float('concentrate_total');

            $table->string('ingredient_name');
            $table->float('ingredient_qty_kg');
            $table->float('ingredient_price_kg');
            $table->float('ingredient_total');
        });
    }
};

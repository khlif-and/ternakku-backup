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
        Schema::table('feeding_inividu_d', function (Blueprint $table) {
            // Mengganti nama kolom ingredient menjadi feed_material
            $table->renameColumn('ingredient_name', 'feed_material_name');
            $table->renameColumn('ingredient_qty_kg', 'feed_material_qty_kg');
            $table->renameColumn('ingredient_price_kg', 'feed_material_price_kg');
            $table->renameColumn('ingredient_total', 'feed_material_total');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feeding_inividu_d', function (Blueprint $table) {
            // Mengembalikan nama kolom ke ingredient
            $table->renameColumn('feed_material_name', 'ingredient_name');
            $table->renameColumn('feed_material_qty_kg', 'ingredient_qty_kg');
            $table->renameColumn('feed_material_price_kg', 'ingredient_price_kg');
            $table->renameColumn('feed_material_total', 'ingredient_total');
        });
    }
};

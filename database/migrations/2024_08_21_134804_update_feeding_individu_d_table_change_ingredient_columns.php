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
    Schema::table('feeding_individu_d', function (Blueprint $table) {
        $table->string('feed_material_name')->nullable()->after('livestock_id');
        $table->decimal('feed_material_qty_kg', 10, 2)->nullable();
        $table->decimal('feed_material_price_kg', 10, 2)->nullable();
        $table->decimal('feed_material_total', 10, 2)->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feeding_individu_d', function (Blueprint $table) {
            // Mengembalikan nama kolom ke ingredient
            $table->renameColumn('feed_material_name', 'ingredient_name');
            $table->renameColumn('feed_material_qty_kg', 'ingredient_qty_kg');
            $table->renameColumn('feed_material_price_kg', 'ingredient_price_kg');
            $table->renameColumn('feed_material_total', 'ingredient_total');
        });
    }
};

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
        $columns = [
            'forage_name', 'forage_qty_kg', 'forage_price_kg', 'forage_total',
            'concentrate_name', 'concentrate_qty_kg', 'concentrate_price_kg', 'concentrate_total',
            'feed_material_name', 'feed_material_qty_kg', 'feed_material_price_kg', 'feed_material_total',
        ];

        foreach ($columns as $col) {
            if (Schema::hasColumn('feeding_individu_d', $col)) {
                $table->dropColumn($col);
            }
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feeding_individu_d', function (Blueprint $table) {
            // Forage details
            $table->string('forage_name')->nullable();
            $table->float('forage_qty_kg')->nullable();
            $table->float('forage_price_kg')->nullable();
            $table->float('forage_total')->nullable();

            // Concentrate details
            $table->string('concentrate_name')->nullable();
            $table->float('concentrate_qty_kg')->nullable();
            $table->float('concentrate_price_kg')->nullable();
            $table->float('concentrate_total')->nullable();

            // Feed ingredient details
            $table->string('feed_material_name')->nullable();
            $table->float('feed_material_qty_kg')->nullable();
            $table->float('feed_material_price_kg')->nullable();
            $table->float('feed_material_total')->nullable();
        });
    }
};

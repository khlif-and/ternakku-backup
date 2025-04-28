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
        Schema::table('qurban_sale_livestock_d', function (Blueprint $table) {
            $table->float('min_weight', 8, 2)->change();
            $table->float('max_weight', 8, 2)->change();
            $table->float('price_per_kg', 12, 2)->change();
            $table->float('price_per_head', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_sale_livestock_d', function (Blueprint $table) {
            $table->float('min_weight')->change();
            $table->float('max_weight')->change();
            $table->float('price_per_kg')->change();
            $table->float('price_per_head')->change();
        });
    }
};

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
            $table->dropColumn(['min_weight', 'max_weight']);
            $table->float('weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_sale_livestock_d', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->float('min_weight')->nullable();
            $table->float('max_weight')->nullable();

        });
    }
};

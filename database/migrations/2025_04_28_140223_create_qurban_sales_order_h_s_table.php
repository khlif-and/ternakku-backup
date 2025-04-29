<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qurban_sales_orders', function (Blueprint $table) {
            $table->dropColumn(['total_weight', 'quantity', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_sales_orders', function (Blueprint $table) {
            $table->float('total_weight');
            $table->integer('quantity');
            $table->longText('description')->nullable();
        });
    }

};

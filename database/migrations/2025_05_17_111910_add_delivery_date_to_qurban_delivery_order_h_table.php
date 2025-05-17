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
        Schema::table('qurban_delivery_order_h', function (Blueprint $table) {
            $table->date('delivery_schedule')->nullable()->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_delivery_order_h', function (Blueprint $table) {
            $table->dropColumn('delivery_schedule');
        });
    }
};

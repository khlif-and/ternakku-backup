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
            $table->date('delivery_plan_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('qurban_sale_livestock_d', function (Blueprint $table) {
            $table->dropColumn('delivery_plan_date');
        });
    }

};

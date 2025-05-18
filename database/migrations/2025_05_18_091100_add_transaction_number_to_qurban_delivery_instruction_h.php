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
        Schema::table('qurban_delivery_instruction_h', function (Blueprint $table) {
            $table->string('transaction_number')->nullable()->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_delivery_instruction_h', function (Blueprint $table) {
            $table->dropColumn('transaction_number');
        });
    }
};

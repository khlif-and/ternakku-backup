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
        Schema::table('qurban_partner_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('livestock_type_id')->nullable();
            $table->foreign('livestock_type_id')->references('id')->on('livestock_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_partner_prices', function (Blueprint $table) {
            $table->dropForeign(['livestock_type_id']);
            $table->dropColumn(['livestock_type_id']);
        });
    }
};

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
        Schema::table('livestocks', function (Blueprint $table) {
            // Add unique constraint for eartag_number with farm_id and livestock_type_id
            $table->unique(['farm_id', 'livestock_type_id', 'eartag_number'], 'unique_eartag_per_farm_and_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropUnique('unique_eartag_per_farm_and_type');
    }
};

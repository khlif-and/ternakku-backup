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
        Schema::table('reproduction_cycles', function (Blueprint $table) {
            DB::statement("ALTER TABLE reproduction_cycles MODIFY COLUMN insemination_type ENUM('artificial', 'natural', 'unknown') NOT NULL");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reproduction_cycles', function (Blueprint $table) {
            DB::statement("ALTER TABLE reproduction_cycles MODIFY COLUMN insemination_type ENUM('artificial', 'natural') NOT NULL");
        });

    }
};

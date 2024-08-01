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
        Schema::table('livestock_breeds', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('photo');
            $table->integer('min_weight')->nullable()->after('description');
            $table->integer('max_weight')->nullable()->after('min_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock_breeds', function (Blueprint $table) {
            $table->dropColumn('max_weight');
            $table->dropColumn('min_weight');
            $table->dropColumn('description');
        });
    }
};

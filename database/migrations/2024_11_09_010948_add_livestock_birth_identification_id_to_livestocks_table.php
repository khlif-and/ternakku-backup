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
            $table->unsignedBigInteger('livestock_birth_identification_id')->nullable();
            $table->foreign('livestock_birth_identification_id')->references('id')->on('livestock_birth_identifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestocks', function (Blueprint $table) {
            $table->dropForeign(['livestock_birth_identification_id']);
            $table->dropColumn(['livestock_birth_identification_id']);
        });
    }
};

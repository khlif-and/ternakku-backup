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
        Schema::table('livestock_birth_d', function (Blueprint $table) {
            $table->unsignedBigInteger('livestock_breed_id')->nullable();
            $table->foreign('livestock_breed_id')->references('id')->on('livestock_breeds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock_birth_d', function (Blueprint $table) {
            $table->dropForeign(['livestock_breed_id']);
            $table->dropColumn(['livestock_breed_id']);
        });
    }
};

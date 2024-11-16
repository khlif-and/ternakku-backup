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
            $table->unsignedBigInteger('bcs_id')->nullable();
            $table->foreign('bcs_id')->references('id')->on('bcs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestocks', function (Blueprint $table) {
            $table->dropForeign(['bcs_id']);
            $table->dropColumn('bcs_id');
        });
    }
};

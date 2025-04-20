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
        // Schema::table('qurban_payments', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreign('qurban_sale_livestock_h_id')->references('id')->on('qurban_sale_livestock_h')->onDelete('cascade');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('qurban_payments');
    }
};

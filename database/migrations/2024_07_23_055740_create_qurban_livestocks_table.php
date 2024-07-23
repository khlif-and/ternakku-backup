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
        Schema::create('qurban_livestock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_id');
            $table->decimal('price', 15, 2);
            $table->timestamps();

            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_livestock');
    }
};

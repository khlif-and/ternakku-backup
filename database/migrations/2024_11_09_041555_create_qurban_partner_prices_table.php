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
        Schema::create('qurban_partner_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->integer('order');
            $table->string('name')->nullable();
            $table->integer('start_weight');
            $table->integer('end_weight');
            $table->float('price_per_kg');

            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_partner_prices');
    }
};

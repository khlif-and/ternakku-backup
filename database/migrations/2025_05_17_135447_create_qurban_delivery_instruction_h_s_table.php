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
        Schema::table('qurban_delivery_order_h', function (Blueprint $table) {
            $table->dropColumn('delivery_schedule');
        });

        Schema::create('qurban_delivery_instruction_h', function (Blueprint $table) {
            $table->id();
            $table->date('delivery_date');
            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('fleet_id');
            $table->enum('status', ['scheduled', 'in_delivery', 'delivered'])->default('scheduled');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fleet_id')->references('id')->on('qurban_fleets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_delivery_instruction_h');

        Schema::table('qurban_delivery_order_h', function (Blueprint $table) {
            $table->date('delivery_schedule')->nullable()->before('created_at');
        });
    }
};

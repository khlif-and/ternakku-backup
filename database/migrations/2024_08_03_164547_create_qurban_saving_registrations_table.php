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
        Schema::create('qurban_saving_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_breed_id');
            $table->unsignedBigInteger('farm_id');
            $table->decimal('weight', 8, 2); // Berat
            $table->decimal('price_per_kg', 8, 2); // Harga per kg
            $table->unsignedBigInteger('region_id');
            $table->string('postal_code');
            $table->string('address_line')->nullable();
            $table->integer('duration_months'); // Waktu tabungan dalam bulan
            $table->timestamps();

            $table->foreign('livestock_breed_id')->references('id')->on('livestock_breeds')->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_saving_registrations');
    }
};

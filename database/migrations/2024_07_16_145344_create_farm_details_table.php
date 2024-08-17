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
        Schema::create('farm_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('region_id');
            $table->string('postal_code');
            $table->string('address_line')->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->integer('capacity');
            $table->string('logo');

            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_details');
    }
};

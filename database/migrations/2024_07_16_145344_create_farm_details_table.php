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
            $table->char('province_id', 2);
            $table->char('regency_id' , 4);
            $table->char('district_id' , 7);
            $table->char('village_id' , 10);
            $table->string('postal_code');
            $table->string('address_line');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->integer('capacity');
            $table->string('logo');

            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');

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

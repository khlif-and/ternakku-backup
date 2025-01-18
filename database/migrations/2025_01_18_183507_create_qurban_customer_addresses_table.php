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
        Schema::create('qurban_customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qurban_customer_id');
            $table->unsignedBigInteger('region_id');
            $table->string('postal_code');
            $table->string('address_line')->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->longText('description')->nullable();

            $table->foreign('qurban_customer_id')->references('id')->on('qurban_customers')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_customer_addresses');
    }
};

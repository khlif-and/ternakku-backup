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
        Schema::create('feed_medicine_purchase_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feed_medicine_purchase_h_id');
            $table->enum('purchase_type', ['forage', 'concentrate' , 'medicine']);
            $table->string('item_name');
            $table->decimal('quantity', 8, 2);
            $table->string('unit');
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            $table->foreign('feed_medicine_purchase_h_id')->references('id')->on('feed_medicine_purchase_h')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_medicine_purchase_d');
    }
};

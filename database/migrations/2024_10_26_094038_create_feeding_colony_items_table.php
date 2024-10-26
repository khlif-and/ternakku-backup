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
        Schema::create('feeding_colony_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feeding_colony_d_id');
            $table->enum('type', ['forage', 'concentrate' , 'feed_material']);
            $table->string('name');
            $table->decimal('qty_kg' , 8 , 2);
            $table->decimal('price_per_kg' , 10 , 2);
            $table->decimal('total_price' , 10 , 2);
            $table->timestamps();
            $table->foreign('feeding_colony_d_id')->references('id')->on('feeding_colony_d')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_colony_item');
    }
};

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
        Schema::create('milk_production_colony_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('milk_production_h_id');
            $table->unsignedBigInteger('pen_id');

            $table->enum('milking_shift', ['morning', 'afternoon']);
            $table->time('milking_time');
            $table->string('milker_name');
            $table->string('milk_condition')->nullable();
            $table->integer('total_livestock');
            $table->float('quantity_liters', 8, 2);
            $table->float('average_liters', 8 , 2);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('milk_production_h_id')->references('id')->on('milk_production_h')->onDelete('cascade');
            $table->foreign('pen_id')->references('id')->on('pens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_production_colony_d');
    }
};

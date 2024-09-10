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
        Schema::create('treatment_individu_medicine_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_individu_d_id')->nullable();
            $table->string('name');
            $table->string('unit');
            $table->float('qty_per_unit');
            $table->float('price_per_unit');
            $table->float('total_price');
            $table->timestamps();

            $table->foreign('treatment_individu_d_id', 'treatment_individu_d_medicine_fk')
                ->references('id')->on('treatment_individu_d')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_individu_medicine_item');
    }
};

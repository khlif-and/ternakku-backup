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
        Schema::create('milk_production_colony_livestock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('milk_production_colony_d_id');
            $table->unsignedBigInteger('livestock_id');
            $table->timestamps();

            $table->foreign('milk_production_colony_d_id', 'mpc_d_id_foreign')
                ->references('id')->on('milk_production_colony_d')->onDelete('cascade');

            $table->foreign('livestock_id', 'livestock_id_foreign')
                ->references('id')->on('livestocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_production_colony_livestock');
    }
};

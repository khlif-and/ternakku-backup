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
        Schema::create('treatment_schedule_individu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_schedule_id');
            $table->unsignedBigInteger('livestock_id');
            $table->string('medicine_name')->nullable();
            $table->string('medicine_unit')->nullable();
            $table->float('medicine_qty_per_unit')->nullable();
            $table->string('treatment_name')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('treatment_schedule_id')->references('id')->on('treatment_schedules')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_schedule_individu');
    }
};

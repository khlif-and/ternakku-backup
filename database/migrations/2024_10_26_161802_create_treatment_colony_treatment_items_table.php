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
        Schema::create('treatment_colony_treatment_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_colony_d_id')->nullable();
            $table->string('name');
            $table->float('cost');
            $table->timestamps();

            $table->foreign('treatment_colony_d_id', 'treatment_colony_d_fk')
                ->references('id')->on('treatment_colony_d')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_colony_treatment_item');
    }
};

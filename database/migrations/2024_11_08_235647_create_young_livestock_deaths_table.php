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
        Schema::create('young_livestock_death', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_birth_d_id');
            $table->date('death_date');
            $table->unsignedBigInteger('disease_id')->nullable();
            $table->string('indication')->nullable();
            $table->foreign('disease_id')->references('id')->on('diseases')->onDelete('cascade');
            $table->foreign('livestock_birth_d_id')->references('id')->on('livestock_birth_d')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('young_livestock_death');
    }
};

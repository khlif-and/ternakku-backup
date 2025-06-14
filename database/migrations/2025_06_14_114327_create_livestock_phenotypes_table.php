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
        Schema::create('livestock_phenotypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained()->onDelete('cascade');

            $table->float('height')->nullable();         // TinggiBadan
            $table->float('body_length')->nullable();    // PanjangBadan
            $table->float('hip_height')->nullable();     // TinggiPinggul
            $table->float('hip_width')->nullable();      // LebarPinggul
            $table->float('chest_width')->nullable();    // LebarDada
            $table->float('head_length')->nullable();    // PanjangKepala
            $table->float('head_width')->nullable();     // LebarKepala
            $table->float('ear_length')->nullable();     // PanjangTelinga
            $table->float('body_weight')->nullable();    // BobotBadan

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_phenotypes');
    }
};

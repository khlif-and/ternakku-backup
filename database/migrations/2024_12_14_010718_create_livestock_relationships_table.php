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
        Schema::create('livestock_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('livestocks');
            $table->foreignId('parent_id')->constrained('livestocks');
            $table->foreignId('sire_breed_id')->constrained('livestock_breeds');
            $table->string('sire_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_relationships');
    }
};

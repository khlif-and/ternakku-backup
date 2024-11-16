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
        Schema::create('livestock_birth_identifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_birth_d_id');
            $table->date('identification_date');
            $table->string('eartag_number');
            $table->string('rfid_number')->nullable();
            $table->foreign('livestock_birth_d_id')->references('id')->on('livestock_birth_d')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_birth_identifications');
    }
};

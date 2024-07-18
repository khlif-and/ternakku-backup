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
        Schema::create('livestock_breeds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('livestock_type_id');
            $table->string('name');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('livestock_type_id')->references('id')->on('livestock_types')->onDelete('cascade');
            $table->unique(['farm_id', 'livestock_type_id' , 'name']);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_breeds');
    }
};

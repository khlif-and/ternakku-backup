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
        Schema::create('livestock_reweight_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_reweight_h_id');
            $table->unsignedBigInteger('livestock_id');
            $table->decimal('weight', 8, 2);
            $table->string('photo')->nullable();
            $table->timestamps();

            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
            $table->foreign('livestock_reweight_h_id')->references('id')->on('livestock_reweight_h')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_reweight_d');
    }
};

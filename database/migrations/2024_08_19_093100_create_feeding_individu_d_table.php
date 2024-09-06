<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedingIndividuDTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('feeding_individu_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feeding_h_id');
            $table->unsignedBigInteger('livestock_id');

            // Additional fields
            $table->float('total_cost');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('feeding_h_id')->references('id')->on('feeding_h')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_individu_d');
    }
}

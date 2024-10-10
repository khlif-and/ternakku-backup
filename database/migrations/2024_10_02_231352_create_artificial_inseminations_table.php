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
        Schema::create('insemination_artificial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reproduction_cycle_id');
            $table->unsignedBigInteger('insemination_id');
            $table->time('action_time');
            $table->string('officer_name')->nullable();
            $table->tinyInteger('insemination_number');
            $table->tinyInteger('pregnant_number');
            $table->tinyInteger('children_number');
            $table->unsignedBigInteger('semen_breed_id');
            $table->string('sire_name');
            $table->string('semen_producer');
            $table->string('semen_batch');
            $table->date('cycle_date');
            $table->float('cost');
            $table->timestamps();

            $table->foreign('reproduction_cycle_id')->references('id')->on('reproduction_cycles')->onDelete('cascade');
            $table->foreign('insemination_id')->references('id')->on('inseminations')->onDelete('cascade');
            $table->foreign('semen_breed_id')->references('id')->on('livestock_breeds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insemination_artificial');
    }
};

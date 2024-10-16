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
        Schema::create('pregnant_check_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reproduction_cycle_id');
            $table->unsignedBigInteger('pregnant_check_id');
            $table->time('action_time');
            $table->string('officer_name')->nullable();
            $table->tinyInteger('pregnant_number');
            $table->tinyInteger('children_number');
            $table->enum('status', ['PREGNANT', 'NOT_PREGNANT']);
            $table->tinyInteger('pregnant_age')->nullable();
            $table->date('estimated_birth_date')->nullable();
            $table->float('cost');
            $table->timestamps();

            $table->foreign('reproduction_cycle_id')->references('id')->on('reproduction_cycles')->onDelete('cascade');
            $table->foreign('pregnant_check_id')->references('id')->on('pregnant_checks')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregnant_check_d');
    }
};

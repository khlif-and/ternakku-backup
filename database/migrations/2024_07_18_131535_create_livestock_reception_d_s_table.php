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
        Schema::create('livestock_reception_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_reception_h_id');
            $table->string('eartag_number');
            $table->string('rfid_number')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('breed_id');
            $table->unsignedBigInteger('sex_id');
            $table->unsignedBigInteger('pen_id');
            $table->integer('age_years');
            $table->integer('age_months');
            $table->decimal('weight', 8, 2);
            $table->decimal('price_per_kg', 8, 2);
            $table->decimal('price_per_head', 10, 2);
            $table->string('photo')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('livestock_reception_h_id')->references('id')->on('livestock_reception_h')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('livestock_types')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('livestock_groups')->onDelete('cascade');
            $table->foreign('breed_id')->references('id')->on('livestock_breeds')->onDelete('cascade');
            $table->foreign('sex_id')->references('id')->on('livestock_sexes')->onDelete('cascade');
            $table->foreign('pen_id')->references('id')->on('pens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_reception_d_s');
    }
};

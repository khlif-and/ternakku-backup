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
        Schema::create('livestocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_reception_d_id')->nullable();
            $table->unsignedBigInteger('livestock_status_id');
            $table->string('eartag_number');
            $table->string('rfid_number')->nullable();
            $table->unsignedBigInteger('livestock_type_id');
            $table->unsignedBigInteger('livestock_group_id');
            $table->unsignedBigInteger('livestock_breed_id');
            $table->unsignedBigInteger('livestock_sex_id');
            $table->unsignedBigInteger('pen_id');
            $table->integer('start_age_years')->nullable();
            $table->integer('start_age_months')->nullable();
            $table->decimal('last_weight', 8, 2);
            $table->string('photo')->nullable();
            $table->timestamps();


            // Foreign key constraints
            $table->foreign('livestock_reception_d_id')->references('id')->on('livestock_reception_d')->onDelete('cascade');
            $table->foreign('livestock_status_id')->references('id')->on('livestock_statuses')->onDelete('cascade');

            $table->foreign('livestock_type_id')->references('id')->on('livestock_types')->onDelete('cascade');
            $table->foreign('livestock_group_id')->references('id')->on('livestock_groups')->onDelete('cascade');
            $table->foreign('livestock_breed_id')->references('id')->on('livestock_breeds')->onDelete('cascade');
            $table->foreign('livestock_sex_id')->references('id')->on('livestock_sexes')->onDelete('cascade');
            $table->foreign('pen_id')->references('id')->on('pens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestocks');
    }
};

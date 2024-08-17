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
        Schema::table('livestocks', function (Blueprint $table) {
            $table->string('eartag_number')->nullable();
            $table->string('rfid_number')->nullable();
            $table->unsignedBigInteger('livestock_type_id')->nullable();
            $table->unsignedBigInteger('livestock_group_id')->nullable();
            $table->unsignedBigInteger('livestock_breed_id')->nullable();
            $table->unsignedBigInteger('livestock_sex_id')->nullable();
            $table->unsignedBigInteger('pen_id')->nullable();
            $table->integer('start_age_years')->nullable();
            $table->integer('start_age_months')->nullable();
            $table->decimal('last_weight', 8, 2)->nullable();
            $table->string('photo')->nullable();


            // Foreign key constraints
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
        Schema::table('livestocks', function (Blueprint $table) {
            //
        });
    }
};

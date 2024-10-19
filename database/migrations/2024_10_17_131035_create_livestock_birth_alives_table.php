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
        Schema::create('livestock_birth_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livestock_birth_id');
            $table->unsignedBigInteger('livestock_sex_id');
            $table->decimal('weight', 8, 2);
            $table->unsignedSmallInteger('birth_order');
            $table->enum('status', ['alive', 'dead']);

            $table->decimal('offspring_value', 10, 2)->nullable();
            $table->unsignedBigInteger('disease_id')->nullable();
            $table->string('indication')->nullable();


            $table->timestamps();
            $table->foreign('livestock_birth_id')->references('id')->on('livestock_birth')->onDelete('cascade');
            $table->foreign('disease_id')->references('id')->on('diseases')->onDelete('cascade');
            $table->foreign('livestock_sex_id')->references('id')->on('livestock_sexes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_birth_d');
    }
};

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
        Schema::table('treatment_colony_d', function (Blueprint $table) {
            // Hapus kolom diagnosis
            $table->dropColumn('diagnosis');

            // Tambahkan kolom disease_id
            $table->unsignedBigInteger('disease_id')->nullable();

            // Tambahkan foreign key constraint (opsional)
            $table->foreign('disease_id')->references('id')->on('diseases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_colony_d', function (Blueprint $table) {
            // Tambahkan kembali kolom diagnosis
            $table->string('diagnosis')->nullable();

            // Hapus kolom disease_id dan constraint-nya
            $table->dropForeign(['disease_id']);
            $table->dropColumn('disease_id');

        });
    }
};

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
        Schema::table('treatment_individu_d', function (Blueprint $table) {
            // Menghapus kolom 'diagnosis'
            $table->dropColumn('diagnosis');

            // Menambahkan kolom 'disease_id' sebagai foreign key
            $table->unsignedBigInteger('disease_id')->nullable()->after('livestock_id');

            // Tambahkan foreign key constraint dengan cascade
            $table->foreign('disease_id')->references('id')->on('diseases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_individu_d', function (Blueprint $table) {
            // Mengembalikan perubahan
            $table->dropForeign(['disease_id']);
            $table->dropColumn('disease_id');
            $table->string('diagnosis')->nullable()->after('livestock_id');
        });
    }
};

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
        Schema::table('livestock_reception_h', function (Blueprint $table) {
            // Hapus foreign key constraint dan kolom supplier_id
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');

            // Tambahkan kolom supplier dengan tipe string
            $table->string('supplier')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock_reception_h', function (Blueprint $table) {
            // Tambahkan kembali kolom supplier_id dan foreign key constraint
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            // Hapus kolom supplier
            $table->dropColumn('supplier');
        });
    }
};

<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus semua data dari tabel turunan terlebih dahulu
        DB::table('qurban_sale_livestock_h')->delete();
        DB::table('qurban_sales_orders')->delete();
        DB::table('qurban_customer_addresses')->delete();

        // Hapus semua data dari qurban_customers
        DB::table('qurban_customers')->delete();

        Schema::table('qurban_customers', function (Blueprint $table) {
            // Drop name & phone_number
            $table->dropColumn(['name', 'phone_number']);

            // Tambahkan user_id
            $table->unsignedBigInteger('user_id')->after('id')->nullable(); // nullable opsional

            // Foreign key ke users.id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->unique(['farm_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_customers', function (Blueprint $table) {
            // Tambahkan kembali kolom yang dihapus
            $table->string('name')->nullable();
            $table->string('phone_number')->nullable();

            // Drop foreign key dan kolom user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

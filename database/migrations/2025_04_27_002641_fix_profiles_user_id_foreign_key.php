<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Drop foreign key lama
            $table->dropForeign(['user_id']);
            
            // Drop kolom user_id kalau mau lebih bersih (opsional, kalau mau recreate dari awal)
            // $table->dropColumn('user_id');

            // Kalau tidak drop column, langsung redefine foreign key saja:
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Balikin ke kondisi sebelumnya (optional)
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('farms')->onDelete('cascade');
        });
    }
};

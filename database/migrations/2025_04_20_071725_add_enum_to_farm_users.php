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
        Schema::table('farm_users', function (Blueprint $table) {
            DB::statement("ALTER TABLE farm_users MODIFY COLUMN farm_role ENUM('OWNER', 'ABK', 'ADMIN', 'MARKETING', 'DRIVER') DEFAULT 'OWNER'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farm_users', function (Blueprint $table) {
            DB::statement("ALTER TABLE farm_users MODIFY COLUMN farm_role ENUM('OWNER', 'ABK', 'ADMIN') DEFAULT 'OWNER'");
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qurban_delivery_order_h', function (Blueprint $table) {
            $table->string('file')->nullable()->before('created_at');
        });
    }
    
    public function down(): void
    {
        Schema::table('qurban_delivery_order_h', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }    
};

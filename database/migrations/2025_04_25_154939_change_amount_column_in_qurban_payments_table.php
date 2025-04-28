<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('qurban_payments', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('qurban_payments', function (Blueprint $table) {
            $table->float('amount')->change(); // balik ke default (biasanya float(8,2))
        });
    }

};

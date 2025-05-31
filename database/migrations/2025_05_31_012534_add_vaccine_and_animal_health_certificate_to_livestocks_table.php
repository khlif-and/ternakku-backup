<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('livestocks', function (Blueprint $table) {
            $table->string('vaccine')->nullable()->before('updated_at');
            $table->string('skkh')->nullable()->after('vaccine');
        });
    }

    public function down()
    {
        Schema::table('livestocks', function (Blueprint $table) {
            $table->dropColumn(['vaccine', 'skkh']);
        });
    }
};

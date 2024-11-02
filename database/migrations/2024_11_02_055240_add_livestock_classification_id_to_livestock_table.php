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
        Schema::table('livestocks', function (Blueprint $table) {
            $table->unsignedBigInteger('livestock_classification_id')->nullable();
            $table->foreign('livestock_classification_id')->references('id')->on('livestock_classifications')->onDelete('cascade');
        });

        Schema::table('livestock_reception_d', function (Blueprint $table) {
            $table->unsignedBigInteger('livestock_classification_id')->nullable();
            $table->foreign('livestock_classification_id')->references('id')->on('livestock_classifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livestock_reception_d', function (Blueprint $table) {
            $table->dropForeign(['livestock_classification_id']);
            $table->dropColumn('livestock_classification_id');
        });

        Schema::table('livestocks', function (Blueprint $table) {
            $table->dropForeign(['livestock_classification_id']);
            $table->dropColumn('livestock_classification_id');
        });
    }
};

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
        Schema::create('livestock_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained('livestocks')->onDelete('cascade');
            $table->foreignId('livestock_expense_type_id')->constrained('livestock_expense_types')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_expenses');
    }
};

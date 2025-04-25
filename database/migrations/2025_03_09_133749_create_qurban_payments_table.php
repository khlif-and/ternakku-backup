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
        Schema::create('qurban_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->date('transaction_date');
            $table->unsignedBigInteger('qurban_customer_id');
            $table->unsignedBigInteger('livestock_id');
            $table->float('amount');
            $table->foreign('qurban_customer_id')->references('id')->on('qurban_customers')->onDelete('cascade');
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_payments');
    }
};

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
        Schema::create('qurban_sale_livestock_payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number');
            $table->date('transaction_date');
            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('qurban_customer_id');
            $table->unsignedBigInteger('qurban_sale_livestock_h_id');
            $table->unsignedBigInteger('created_by')->nullable(); 
            $table->decimal('amount', 12, 2);
            $table->foreign('qurban_customer_id')->references('id')->on('qurban_customers')->onDelete('cascade');
            $table->foreign('qurban_sale_livestock_h_id', 'qslp_hid_fk')
                ->references('id')
                ->on('qurban_sale_livestock_h')
                ->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_sale_livestock_payments');
    }
};

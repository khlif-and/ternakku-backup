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
        Schema::create('subscription_farm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id')->nullable();
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->integer('quantity');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('confirmation_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_farm');
    }
};

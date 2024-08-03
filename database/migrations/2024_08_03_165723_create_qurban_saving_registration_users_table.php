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
        Schema::create('qurban_saving_registration_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qurban_saving_registration_id');
            $table->unsignedBigInteger('user_bank_id');
            $table->tinyInteger('portion')->default(1);
            $table->timestamps();

            // Define foreign key constraints with custom names
            $table->foreign('qurban_saving_registration_id', 'qssr_qss_id_foreign')
                ->references('id')
                ->on('qurban_saving_registrations')
                ->onDelete('cascade');

            $table->foreign('user_bank_id', 'qssr_bu_id_foreign')
                ->references('id')
                ->on('user_bank') // Update this to 'user_bank' instead of 'bank_user'
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_saving_registration_user');
    }
};

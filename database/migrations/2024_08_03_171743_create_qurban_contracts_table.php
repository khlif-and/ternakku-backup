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
        Schema::create('qurban_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qurban_saving_registration_id');
            $table->unsignedBigInteger('livestock_breed_id');
            $table->decimal('weight', 8, 2);
            $table->decimal('price_per_kg', 8, 2);
            $table->char('province_id', 2);
            $table->char('regency_id', 4);
            $table->char('district_id', 7);
            $table->char('village_id', 10);
            $table->string('postal_code');
            $table->string('address_line')->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->date('contract_date');
            $table->decimal('down_payment', 10, 2);
            $table->unsignedBigInteger('farm_id');
            $table->date('estimated_delivery_date');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('qurban_saving_registration_id', 'qurban_contract_qss_id_foreign')
                ->references('id')
                ->on('qurban_saving_registrations')
                ->onDelete('cascade');

            $table->foreign('livestock_breed_id', 'qurban_contract_livestock_breed_id_foreign')
                ->references('id')
                ->on('livestock_breeds')
                ->onDelete('cascade');

            $table->foreign('farm_id', 'qurban_contract_farm_id_foreign')
                ->references('id')
                ->on('farms')
                ->onDelete('cascade');

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_contracts');
    }
};

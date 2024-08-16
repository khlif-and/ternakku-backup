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
        Schema::table('qurban_contracts', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        Schema::table('qurban_saving_registrations', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        Schema::table('farm_details', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        Schema::drop('villages');

        Schema::drop('districts');

        Schema::drop('regencies');

        Schema::drop('provinces');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('provinces', function(Blueprint $table){
            $table->char('id', 2)->index();
            $table->string('name');
        });

        Schema::create('regencies', function(Blueprint $table){
            $table->char('id', 4)->index();
            $table->char('province_id', 2);
            $table->string('name', 50);
            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('districts', function(Blueprint $table){
            $table->char('id', 7)->index();
            $table->char('regency_id', 4);
            $table->string('name', 50);
            $table->foreign('regency_id')
                ->references('id')
                ->on('regencies')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('villages', function(Blueprint $table){
            $table->char('id', 10)->index();
            $table->char('district_id', 7);
            $table->string('name', 50);
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::table('qurban_contracts', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
            $table->char('province_id', 2);
            $table->char('regency_id', 4);
            $table->char('district_id', 7);
            $table->char('village_id', 10);
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
        });

        Schema::table('qurban_saving_registrations', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
            $table->char('province_id', 2);
            $table->char('regency_id', 4);
            $table->char('district_id', 7);
            $table->char('village_id', 10);
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
            $table->char('province_id', 2);
            $table->char('regency_id', 4);
            $table->char('district_id', 7);
            $table->char('village_id', 10);
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
        });

        Schema::table('farm_details', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
            $table->char('province_id', 2);
            $table->char('regency_id', 4);
            $table->char('district_id', 7);
            $table->char('village_id', 10);
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
        });
    }

};

<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('subscriptions')->firstOrCreate(
            ['id' => 1], // Attributes to search by
            [
                'module' => 'farming',
                'name' => 'basic',
            ] // Attributes to create if not found
        );
        $farms = DB::table('farms')->get();

        // Loop melalui setiap farm dan masukkan ke tabel 'subscription_farm'
        foreach ($farms as $farm) {
            $startDate = Carbon::parse($farm->created_at); // Mengambil created_at dari farm sebagai start_date
            $endDate = $startDate->copy()->addDays(30); // end_date adalah start_date + 30 hari

            DB::table('subscription_farm')->insert([
                'farm_id' => $farm->id,
                'subscription_id' => 1, // Sesuai permintaan, subscription_id adalah 1
                'quantity' => 100, // Kuantitas default adalah 1
                'start_date' => $startDate,
                'end_date' => $endDate,
                'confirmation_date' => $startDate, // Sesuai permintaan, confirmation_date sama dengan start_date
                'created_at' => now(), // Waktu pembuatan record ini
                'updated_at' => now(), // Waktu terakhir update record ini
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

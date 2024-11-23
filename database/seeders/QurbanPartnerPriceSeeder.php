<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QurbanPartnerPriceSeeder extends Seeder
{
    public function run()
    {
        // Hapus data existing
        DB::table('qurban_partner_prices')->truncate();

        $ranges = [
            [225, 275],
            [276, 300],
            [301, 325],
            [326, 350],
            [351, 375],
            [376, 400],
            [401, 450],
            [451, 500],
        ];

        $prices = [
            81000,
            80000,
            79000,
            77000,
            75000,
            73000,
            71000,
            70000,
        ];

        $farms = [1, 2, 3];
        $timestamp = Carbon::now();

        foreach ($farms as $farm) {
            $order = 1;
            foreach ($ranges as $index => $range) {
                DB::table('qurban_partner_prices')->insert([
                    'farm_id' => $farm,
                    'order' => $order,
                    'name' => 'paket' . $order,
                    'start_weight' => $range[0],
                    'end_weight' => $range[1],
                    'price_per_kg' => $prices[$index],
                    'previous_price_per_kg' => $prices[$index] - 4000,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                    'discount_percent' => 7,
                ]);
                $order++;
            }
        }
    }
}

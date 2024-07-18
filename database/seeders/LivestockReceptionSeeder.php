<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LivestockReceptionH;
use App\Models\LivestockReceptionD;

class LivestockReceptionSeeder extends Seeder
{
    public function run()
    {
        // // Create 20 LivestockReceptionH records
        // $receptionHeaders = LivestockReceptionH::factory()->count(20)->create();

        // // Create a LivestockReceptionD for each LivestockReceptionH
        // $receptionHeaders->each(function ($receptionHeader) {
        //     LivestockReceptionD::factory()->create([
        //         'livestock_reception_h_id' => $receptionHeader->id,
        //     ]);
        // });

        LivestockReceptionD::factory(20)->create();
    }
}

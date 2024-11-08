<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LivestockClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('livestock_classifications')->insert([
            ['name' => 'laktasi_bunting'],
            ['name' => 'laktasi_kosong'],
            ['name' => 'kering_bunting'],
            ['name' => 'kering_kosong'],
            ['name' => 'dara_bunting'],
            ['name' => 'dara_kosong'],
            ['name' => 'pedet_jantan'],
            ['name' => 'pedet_betina'],
            ['name' => 'pejantan'],
        ]);
    }
}

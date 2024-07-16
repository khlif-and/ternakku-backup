<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Farm;
use App\Models\FarmDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(IndoRegionSeeder::class);
        $this->call(LivestockTypeSeeder::class);
        $this->call(LivestockSexSeeder::class);
        $this->call(LivestockGroupSeeder::class);
        $this->call(UserSeeder::class);

        Farm::factory()
            ->count(3)
            ->has(FarmDetail::factory()->count(1), 'detail')
            ->create();
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Pen;
use App\Models\Farm;
use App\Models\FarmDetail;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
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

        $farms = Farm::factory()
            ->count(3)
            ->has(FarmDetail::factory()->count(1), 'detail')
            ->create();

        // Create pens for each farm
        $farms->each(function ($farm) {
            Pen::factory()->count(rand(1, 5))->create(['farm_id' => $farm->id]);
        });

        // Get all livestock types
        $types = LivestockType::all();

        // Create livestock breeds for each farm
        $farms->each(function ($farm) use ($types) {
            LivestockBreed::factory()->count(rand(1, 5))->create([
                'farm_id' => $farm->id,
                'livestock_type_id' => $types->random()->id,
            ]);
        });
    }
}

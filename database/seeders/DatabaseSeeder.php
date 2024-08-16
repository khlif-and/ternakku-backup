<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Pen;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RegionSeeder::class,
            LivestockTypeSeeder::class,
            LivestockSexSeeder::class,
            LivestockGroupSeeder::class,
            LivestockStatusSeeder::class,
            LivestockBreedSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            FarmSeeder::class,
        ]);

        // Get all livestock types
        $types = LivestockType::all();

        // Create livestock breeds for each farm
        $farms = Farm::all();

        $farms->each(function ($farm) {
            Pen::factory()->count(rand(1, 5))->create(['farm_id' => $farm->id]);
        });


        $this->call([
            LivestockReceptionSeeder::class,
            QurbanLivestockSeeder::class,
            BankSeeder::class,
        ]);
    }
}

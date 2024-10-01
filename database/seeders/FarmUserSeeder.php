<?php

namespace Database\Seeders;

use App\Models\Farm;
use App\Models\FarmUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FarmUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $farms = Farm::all();

        foreach($farms as $farm){
            FarmUser::create([
                'user_id' => $farm->owner_id,
                'farm_id' => $farm->id,
            ]);
        }
    }
}

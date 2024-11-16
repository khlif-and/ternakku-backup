<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BcsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bcs')->insert([
            [
                'name' => 'Terlalu Kurus',
                'lower_limit' => 1.0,
                'upper_limit' => 2.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kurus',
                'lower_limit' => 2.1,
                'upper_limit' => 2.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cukup Ideal',
                'lower_limit' => 2.6,
                'upper_limit' => 3.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ideal',
                'lower_limit' => 3.1,
                'upper_limit' => 3.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gemuk',
                'lower_limit' => 3.6,
                'upper_limit' => 4.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Terlalu Gemuk',
                'lower_limit' => 4.1,
                'upper_limit' => 5.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LivestockStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('livestock_statuses')->insert([
            ['name' => 'hidup'],
            ['name' => 'mati'],
            ['name' => 'terjual'],
        ]);
    }
}

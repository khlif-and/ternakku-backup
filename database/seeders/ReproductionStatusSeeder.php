<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReproductionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    DB::table('reproduction_statuses')->insert([
        ['name' => 'Aktif'],
        ['name' => 'Tidak Aktif'],
    ]);
}

}

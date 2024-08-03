<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bank::create([
            'name' => 'Bank A',
            'swift_code' => 'BANKA123',
        ]);

        Bank::create([
            'name' => 'Bank B',
            'swift_code' => 'BANKB456',
        ]);
    }
}

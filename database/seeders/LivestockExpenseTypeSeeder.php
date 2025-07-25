<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LivestockExpenseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LivestockExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseTypes = [
            ['name' => 'Treatment'],
            ['name' => 'Feeding'],
            ['name' => 'Artificial Insemination'],
            ['name' => 'Natural Insemination'],
        ];

        foreach ($expenseTypes as $type) {
            LivestockExpenseType::firstOrCreate(['name' => $type['name']]);
        }
    }
}

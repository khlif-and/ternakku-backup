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
            ['id' => 1, 'name' => 'Treatment'],
            ['id' => 2, 'name' => 'Feeding'],
            ['id' => 3, 'name' => 'Artificial Insemination'],
            ['id' => 4, 'name' => 'Natural Insemination'],
            ['id' => 5, 'name' => 'Pregnant Check'],
            ['id' => 6, 'name' => 'Birth'],
        ];


        foreach ($expenseTypes as $type) {
            LivestockExpenseType::firstOrCreate(['name' => $type['name']]);
        }
    }
}

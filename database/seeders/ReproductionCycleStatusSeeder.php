<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\ReproductionCycleStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReproductionCycleStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menyiapkan data status reproduksi
        $statuses = [
            [
                'id' => ReproductionCycleStatusEnum::INSEMINATION->value,
                'name' => 'insemination',
            ],
            [
                'id' => ReproductionCycleStatusEnum::INSEMINATION_FAILED->value,
                'name' => 'insemination_failed',
            ],
            [
                'id' => ReproductionCycleStatusEnum::PREGNANT->value,
                'name' => 'pregnant',
            ],
            [
                'id' => ReproductionCycleStatusEnum::GAVE_BIRTH->value,
                'name' => 'gave_birth',
            ],
            [
                'id' => ReproductionCycleStatusEnum::BIRTH_FAILED->value,
                'name' => 'birth_failed',
            ],
            [
                'id' => ReproductionCycleStatusEnum::WEANING->value,
                'name' => 'weaning',
            ],
        ];

        // Menggunakan DB::table untuk menginsert data ke dalam tabel status_reproduksi
        DB::table('reproduction_cycle_statuses')->insert($statuses);
    }
}

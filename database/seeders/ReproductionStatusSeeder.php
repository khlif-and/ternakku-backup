<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReproductionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menyiapkan data status reproduksi
        $statuses = [
            [
                'id' => ReproductionStatusEnum::INSEMINATION->value,
                'name' => ReproductionStatusEnum::INSEMINATION->label(),
            ],
            [
                'id' => ReproductionStatusEnum::INSEMINATION_FAILED->value,
                'name' => ReproductionStatusEnum::INSEMINATION_FAILED->label(),
            ],
            [
                'id' => ReproductionStatusEnum::PREGNANT->value,
                'name' => ReproductionStatusEnum::PREGNANT->label(),
            ],
            [
                'id' => ReproductionStatusEnum::GAVE_BIRTH->value,
                'name' => ReproductionStatusEnum::GAVE_BIRTH->label(),
            ],
            [
                'id' => ReproductionStatusEnum::BIRTH_FAILED->value,
                'name' => ReproductionStatusEnum::BIRTH_FAILED->label(),
            ],
            [
                'id' => ReproductionStatusEnum::WEANING->value,
                'name' => ReproductionStatusEnum::WEANING->label(),
            ],
        ];

        // Menggunakan DB::table untuk menginsert data ke dalam tabel status_reproduksi
        DB::table('reproduction_statuses')->insert($statuses);
    }
}

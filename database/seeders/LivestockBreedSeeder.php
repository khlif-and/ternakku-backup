<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LivestockBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke file XLS
        $xlsFile = database_path('csv/livestock_breeds.xlsx');

        // Baca XLS menggunakan PhpSpreadsheet
        $spreadsheet = IOFactory::load($xlsFile);
        $worksheet = $spreadsheet->getActiveSheet();

        $header = [];
        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $record = [];
            foreach ($cellIterator as $cellIndex => $cell) {
                if ($rowIndex == 1) {
                    $header[$cellIndex] = $cell->getValue();
                } else {
                    $record[$header[$cellIndex]] = $cell->getValue();
                }
            }

            if ($rowIndex > 1) {
                DB::table('livestock_breeds')->insert([
                    'livestock_type_id' => $record['livestock_type_id'],
                    'name'              => $record['name'],
                    'photo'             => $record['photo'],
                    'description'       => $record['description'],
                    'min_weight'        => $record['min_weight'],
                    'max_weight'        => $record['max_weight'],
                    'is_qurban'         => $record['is_qurban'],
                ]);
            }
        }
    }

}

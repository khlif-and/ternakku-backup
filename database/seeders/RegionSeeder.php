<?php

namespace Database\Seeders;

use League\Csv\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke file CSV
        $csvFile = database_path('csv/data_wilayah.csv');

        // Baca CSV menggunakan League\Csv
        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0); // Mengatur baris pertama sebagai header

        foreach ($csv as $record) {
            DB::table('regions')->insert([
                'id'            => $record['KodeUnik'],
                'province_id'   => $record['KodePropinsi'],
                'province_name' => $record['NamaPropinsi'],
                'regency_id'    => $record['KodeKabKota'],
                'regency_name'  => $record['NamaKabKota'],
                'district_id'   => $record['KodeKecamatan'],
                'district_name' => $record['NamaKecamatan'],
                'village_id'    => $record['KodeKelurahan'],
                'village_name'  => $record['NamaKelurahan'],
                'name'          => $record['NamaWilayah'],
            ]);
        }
    }
}

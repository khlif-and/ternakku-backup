<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diseases = [
            'Abortion',
            'Anoreksia/Tidak Makan',
            'Arthritis/Radang Sendi',
            'Demam 3 Hari',
            'Bloat/Kembung',
            'Diare',
            'Distokia/Kesulitan Melahirkan',
            'Dislokasio',
            'Edema',
            'Ektoparacite',
            'Endoparacite',
            'Entritis/Radang Pencernaan',
            'Hematoma',
            'Brucellosis',
            'Infeksi Pernafasan Atas',
            'Lame/Pincang',
            'Laktosa Intolerance',
            'Laminitis/Radang Kuku',
            'Lumpy Skin Disease',
            'Metritis/Endometritis',
            'Miasis/Belatungan',
            'Mismothering',
            'Mastitis',
            'Omphalitis/Radang Pusar',
            'Penyakit Mulut Kuku',
            'Pneumonia',
            'Prolaps Uterus',
            'Prolaps Vagina',
            'Scabies',
            'SE/Ngorok',
            'Stillbirth',
            'Wound/Luka',
        ];

        foreach ($diseases as $disease) {
            DB::table('diseases')->insert([
                'name' => $disease,
            ]);
        }
    }
}

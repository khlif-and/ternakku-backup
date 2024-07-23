<?php

namespace Database\Seeders;

use App\Models\Livestock;
use App\Models\QurbanLivestock;
use Illuminate\Database\Seeder;
use App\Enums\LivestockTypeEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QurbanLivestockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $livestocks = Livestock::all();

        foreach ($livestocks as $livestock) {
            if (in_array($livestock->livestockReceptionD->livestock_type_id, [LivestockTypeEnum::SAPI->value, LivestockTypeEnum::KERBAU->value])) { // Sapi dan Kerbau
                $price = rand(15000000, 50000000);
            } elseif (in_array($livestock->livestockReceptionD->livestock_type_id, [LivestockTypeEnum::KAMBING->value, LivestockTypeEnum::DOMBA->value])) { // Kambing dan Domba
                $price = rand(2000000, 10000000);
            }

            QurbanLivestock::create([
                'livestock_id' => $livestock->id,
                'price' => $price,
            ]);

        }

    }
}

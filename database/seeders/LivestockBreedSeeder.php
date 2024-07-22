<?php

namespace Database\Seeders;

use App\Enums\LivestockTypeEnum;
use App\Models\LivestockBreed;
use Illuminate\Database\Seeder;

class LivestockBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        // Define livestock breeds with their corresponding livestock_type_id
        $livestockBreeds = [
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Awassi'],
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Barbados Blackbelly'],
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Dorper'],
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Ekor Gemuk'],
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Ekor Tipis'],
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Garut'],
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Merino'],
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Priangan'],
            ['livestock_type_id' => LivestockTypeEnum::DOMBA, 'name' => 'Texel'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Anglo Nubian'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Boer'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Etawa'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Jawa Randu'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Kacang'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Kaligesing'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Peranakan Etawa'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Saburai'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Saanen'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Senduro'],
            ['livestock_type_id' => LivestockTypeEnum::KAMBING, 'name' => 'Sapera'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Kundhi'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Kalang'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Murrah'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Mehsana'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Nagpuri'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Ravi'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Surti'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Toraya'],
            ['livestock_type_id' => LivestockTypeEnum::KERBAU, 'name' => 'Zaffarabadi'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Angus'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Brahman Angus'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Bali'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Brahman'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Brahman Cross'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Drough Master'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Friesian Holstein'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Kupang'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Limousine'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Limousin PO'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Nellore'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Peranakan Ongole'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Sapi Madura'],
            ['livestock_type_id' => LivestockTypeEnum::SAPI, 'name' => 'Ongole'],
        ];

        // Insert the livestock breeds into the database
        LivestockBreed::insert($livestockBreeds);
    }
}

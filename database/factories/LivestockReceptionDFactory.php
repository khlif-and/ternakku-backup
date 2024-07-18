<?php

namespace Database\Factories;

use App\Models\Pen;
use App\Models\LivestockSex;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
use App\Models\LivestockGroup;
use App\Models\LivestockReceptionD;
use App\Models\LivestockReceptionH;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivestockReceptionDFactory extends Factory
{
    protected $model = LivestockReceptionD::class;

    public function definition()
    {
        $livestockReceptionH = LivestockReceptionH::factory()->create();
        $farmId = $livestockReceptionH->farm_id;
        $livestockBreed = LivestockBreed::where('farm_id', $farmId)->first();
        $livestockTypeId = $livestockBreed->livestock_type_id;

        return [
            'livestock_reception_h_id' => $livestockReceptionH->id,
            'eartag_number' => $this->faker->unique()->numerify('EARTAG###'),
            'rfid_number' => $this->faker->unique()->numerify('RFID###'),
            'livestock_type_id' => $livestockTypeId,
            'livestock_group_id' => LivestockGroup::inRandomOrder()->first()->id,
            'livestock_breed_id' => $livestockBreed->id,
            'livestock_sex_id' => LivestockSex::inRandomOrder()->first()->id,
            'pen_id' => function () use ($farmId) {
                return Pen::where('farm_id', $farmId)->inRandomOrder()->first()->id;
            },
            'age_years' => $this->faker->numberBetween(1, 5),
            'age_months' => $this->faker->numberBetween(0, 11),
            'weight' => $this->faker->randomFloat(2, 100, 500),
            'price_per_kg' => $this->faker->randomFloat(2, 10, 50),
            'price_per_head' => $this->faker->randomFloat(2, 1000, 5000),
            'notes' => $this->faker->sentence,
            'photo' => $this->faker->imageUrl()
        ];
    }
}

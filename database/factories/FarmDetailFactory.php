<?php

namespace Database\Factories;

use App\Models\FarmDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FarmDetail>
 */
class FarmDetailFactory extends Factory
{
    protected $model = FarmDetail::class;

    public function definition()
    {
        return [
            'farm_id' => \App\Models\Farm::factory(),
            'province_id' => 32,
            'regency_id' => 3273,
            'district_id' => 3273230,
            'village_id' => 3273230002,
            'postal_code' => '40132',
            'address_line' => $this->faker->streetAddress,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'capacity' => $this->faker->numberBetween(50, 200),
            'logo' => $this->faker->imageUrl(640, 480, 'farm', true, 'Faker'),
        ];
    }
}

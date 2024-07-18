<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'farm_id' => \App\Models\Farm::factory(),
            'name' => $this->faker->company,
            'province_id' => 32,
            'regency_id' => 3273,
            'district_id' => 3273230,
            'village_id' => 3273230002,
            'postal_code' => '40132',
            'address_line' => $this->faker->streetAddress,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
        ];
    }
}

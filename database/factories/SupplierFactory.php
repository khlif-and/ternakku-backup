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
            'farm_id' => rand(1,3),
            'phone_number' => '6282116654129',
            'name' => $this->faker->company,
            'region_id' => 1101012001,
            'postal_code' => '40132',
            'address_line' => $this->faker->streetAddress,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
        ];
    }
}

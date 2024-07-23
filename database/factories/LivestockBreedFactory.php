<?php

namespace Database\Factories;

use App\Models\LivestockBreed;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivestockBreedFactory extends Factory
{
    protected $model = LivestockBreed::class;

    public function definition()
    {
        return [
            'farm_id' => rand(1,3),
            'name' => $this->faker->unique()->word . ' Breed',
        ];
    }
}

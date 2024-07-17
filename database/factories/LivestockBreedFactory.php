<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\LivestockBreed;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivestockBreedFactory extends Factory
{
    protected $model = LivestockBreed::class;

    public function definition()
    {
        return [
            'farm_id' => Farm::factory(),
            'name' => $this->faker->word . ' Breed',
        ];
    }
}

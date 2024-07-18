<?php

namespace Database\Factories;

use App\Models\Pen;
use App\Models\Farm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pen>
 */
class PenFactory extends Factory
{
    protected $model = Pen::class;

    public function definition()
    {
        return [
            'farm_id' => Farm::factory(), // Ensure a related farm is created
            'name' => $this->faker->unique()->word . ' Pen',
            'area' => $this->faker->numberBetween(100, 500), // Random area between 100 and 500
            'capacity' => $this->faker->numberBetween(10, 50), // Random capacity between 10 and 50
            'photo' => $this->faker->imageUrl(640, 480, 'animals', true, 'Pen'), // Placeholder image URL
        ];
    }

}

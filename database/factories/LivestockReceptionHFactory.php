<?php

namespace Database\Factories;

use App\Models\Farm;
use Illuminate\Support\Carbon;
use App\Models\LivestockReceptionH;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LivestockReceptionH>
 */
class LivestockReceptionHFactory extends Factory
{
    protected $model = LivestockReceptionH::class;

    public function definition()
    {
        return [
            'farm_id' => Farm::inRandomOrder()->first()->id,
            'transaction_date' => Carbon::now(),
            'supplier' => 'CV Sarana Ternak',
            'notes' => $this->faker->sentence
        ];
    }
}

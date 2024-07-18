<?php

namespace Database\Factories;

use App\Models\LivestockBreed;
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
        $livestockBreed = LivestockBreed::inRandomOrder()->first();

        return [
            'farm_id' => $livestockBreed->farm_id,
            'transaction_date' => Carbon::now(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'notes' => $this->faker->sentence
        ];
    }
}

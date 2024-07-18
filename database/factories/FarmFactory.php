<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farm>
 */
class FarmFactory extends Factory
{
    protected $model = Farm::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'registration_date' => Carbon::now(),
            'qurban_partner' => true,
            'owner_id' => User::whereHas('roles', function ($query) {
                $query->where('name', RoleEnum::FARMER->value);
            })->inRandomOrder()->first()->id,
        ];
    }

}

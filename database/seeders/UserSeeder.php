<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
                    'name' => 'Muhammad Iqbal Mubarok',
                    'email' => 'mubarok.iqbal@gmail.com',
                    'phone_number' => '082116654123',
                    'password' => Hash::make('1234567890'),
                    'email_verified_at' => Carbon::now(),
                ]);

        $user2 = User::create([
                    'name' => 'Peternak Bandung',
                    'email' => 'farmer@farmer.com',
                    'phone_number' => '08212345678',
                    'password' => Hash::make('1234567890'),
                    'email_verified_at' => Carbon::now(),
                ]);

        // Attach roles to users with timestamps using enum
        $user1->roles()->attach(Role::getRoleId(RoleEnum::REGISTERED_USER), [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $user2->roles()->attach([
            Role::getRoleId(RoleEnum::REGISTERED_USER) => [
                'created_at' => now(),
                'updated_at' => now()
            ],
            Role::getRoleId(RoleEnum::FARMER) => [
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}

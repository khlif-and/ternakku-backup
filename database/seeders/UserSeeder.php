<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::create([
            'name' => 'Muhammad Iqbal Mubarok',
            'email' => 'mubarok.iqbal@gmail.com',
            'phone_number' => '082116654123',
            'password' => Hash::make('1234567890'),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Farm;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\FarmDetail;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FarmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $farms = [
            [
                'name' => 'CV. Silih Wangi Sawargi',
                'description' => '<p>Sebagai pelaku usaha peternakan sapi dengan pengalaman lebih dari 20 tahun, kredibiltas dan integritas pemilik CV. Silih Wangi Sawargi tidak perlu diragukan lagi. Dan waktu juga kemudian membuktikan bahwa hanya peternak dengan kredibiltas dan integritas yang mampu bertahan bahkan berkembang sehingga memiliki peternakan sapi di beberapa wilayah serta mampu bekerjasama dalam skala Nasional dan Internasional.</p>
                <p>Saat ini CV. Silih Wangi Sawargi memiliki kandang di Ciawi - Tasikmalaya, dengan kapasitas 400 ekor, serta kandang di Darangdan - Purwakarta, dengan kapasitas 600 ekor. Dan berkat pengalaman juga reputasinya di dunia peternakan sapi potong, CV. Silih Wangi Sawargi memiliki kemampuan untuk menyediakan sampai dengan 5.000 ekor sapi kurban</p>',
                'logo' => 'farm/silih-wangi-sawargi.jpeg'
            ],
            [
                'name' => 'PT. Baqara Muda Perkasa',
                'description' => '<p>Merupakan salahsatu peternakan sapi terkemuka yang berlokasi di Jalan Cagak - Subang, Jawa Barat, PT. Baqara Mudah Perkasa secara konsisten melakukan pengembangan usaha peternakan sapi potong yang secara bertahap terus meningkat.</p><p>Dimulai dari tahun 2013, kapasitas kandang PT. Baqara Mudah Perkasa terus berkembang dan saat ini memiliki kapasitas 1.200 ekor sapi.</p>',
                'logo' => 'farm/baqara-farm.jpeg'
            ],
            [
                'name' => 'PT. Sukamulya Hijau Lestari',
                'description' => '<p>Adalah peternakan sapi, domba, dan kambing yang berlokasi di Sukaluyu - Cianjur, didirikan pada tahun 2015 dengan kapasitas 1.000 ekor sapi serta 800 ekor domba dan kambing, menjadikan PT. Sukamulya Hijau Lestari sebagai salah satu peternakan besar di wilayah Cianjur.</p>',
                'logo' => 'farm/shl.jpeg'
            ]
        ];

        foreach($farms as $item){
            $farm = Farm::create([
                        'name' => $item['name'],
                        'registration_date' => Carbon::now(),
                        'qurban_partner' => true,
                        'owner_id' => User::whereHas('roles', function ($query) {
                            $query->where('name', RoleEnum::FARMER->name);
                        })->inRandomOrder()->first()->id,
                    ]);

            $farmDetail = FarmDetail::create([
                'farm_id' => $farm->id,
                'description' => $item['description'],
                'region_id' => 1101012001,
                'postal_code' => '40132',
                'address_line' => 'Tamansari',
                'longitude' => 107.607540,
                'latitude' => -6.901420,
                'capacity' =>rand(50, 200),
                'logo' => $item['logo'],
            ]);
        }
    }
}

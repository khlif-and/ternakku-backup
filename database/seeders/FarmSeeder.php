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
                'description' => '<p><strong>CV. Silih Wangi Sawargi</strong></p>
<p><br></p>
<p>Sebagai pelaku usaha peternakan sapi dengan pengalaman lebih dari 20 tahun, kredibiltas dan integritas pemilik CV. Silih Wangi Sawargi tidak perlu diragukan lagi. Dan waktu juga yang kemudian membuktikan bahwa hanya peternak dengan kredibiltas dan integritas tinggi yang mampu bertahan bahkan berkembang sehingga memiliki peternakan sapi di beberapa wilayah serta mampu bekerjasama dalam skala Nasional maupun Internasional.</p>
<p><br></p>
<p>Saat ini CV. Silih Wangi Sawargi memiliki kandang di Ciawi - Tasikmalaya, dengan kapasitas 400 ekor, serta kandang di Darangdan - Purwakarta, dengan kapasitas 600 ekor. Dan berkat pengalaman juga reputasinya di dunia peternakan sapi potong, CV. Silih Wangi Sawargi memiliki kemampuan untuk menyediakan sampai dengan 5.000 ekor sapi kurban</p>
<p><br></p>
<p>Jenis sapi yang disediakan : Simental, Limousin, Bali, Madura, Silangan</p>
<p><br></p>
<p>Lokasi Kandang Darangdan - Purwakarta : 6&deg;40&apos;57.1&quot;S 107&deg;25&apos;24.7&quot;E</p>
<p>Lokasi Kandang Ciawi - Tasikmalaya : 7&deg;09&apos;09.0&quot;S 108&deg;09&apos;52.9&quot;E</p>',
                'logo' => 'ternak-kurban/mitra-peternak/_01_LogoSWS-NoBG.png'
            ],
            [
                'name' => 'PT. Baqara Muda Perkasa',
                'description' => '<p><strong>PT. Baqara Muda Perkasa</strong></p>
<p><br></p>
<p>Merupakan salahsatu peternakan sapi terkemuka yang berlokasi di Desa Sarireja, Jalan Cagak - Subang, Jawa Barat, PT. Baqara Mudah Perkasa secara konsisten melakukan pengembangan usaha peternakan sapi potong yang secara bertahap terus meningkat.</p>
<p><br></p>
<p>Dimulai dari tahun 2013, PT. Baqara Muda Perkasa sudah melayani lebih dari 1.000 orang pelanggan sapi kurban, dan saat ini PT. Baqara Muda Perkasa memiliki kapasitas kandang yang mampu menampung 1.500 ekor sapi.</p>
<p><br></p>
<p>Jenis sapi yang disediakan : Simental, Limousin, Bali, Madura, Kupang, Ongole, Silangan</p>
<p><br></p>
<p>Lokasi Kandang : 6&deg;41&apos;45.6&quot;S 107&deg;41&apos;49.4&quot;E</p>
<p><br></p>',
            'logo' => 'ternak-kurban/mitra-peternak/_02_LogoBMP-NoBG.png'
            ],
            [
                'name' => 'PT. Sukamulya Hijau Lestari',
                'description' => '<p><strong>PT. Sukamulya Hijau Lestari</strong></p>
<p><br></p>
<p>Adalah peternakan sapi, domba, dan kambing yang berlokasi di Sekaluyu - Cianjur, didirikan pada tahun 2015 dengan kapasitas 1.000 ekor sapi serta 800 ekor domba dan kambing, menjadikan PT. Sukamulya Hijau Lestari sebagai salah satu peternakan besar di wilayah Cianjur.</p>
<p><br></p>
<p>Jenis domba yang disediakan : Simental, Limousin, Bali, Madura, Kupang, Ongole, Silangan</p>
<p><br></p>
<p>Lokasi Kandang : 6&deg;41&apos;45.6&quot;S 107&deg;41&apos;49.4&quot;E</p>
<p><br></p>',
                'logo' => 'ternak-kurban/mitra-peternak/_03_LogoSHL-NoBG.png'
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

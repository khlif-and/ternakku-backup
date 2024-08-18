<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    public function run()
    {
        DB::table('blogs')->insert([
            [
                'slug' => 'mengenai-ternak-kurban',
                'title' => 'Mengenai Ternak Kurban',
                'excerpt' => 'Mulai Kurban Lebih Murah & Mudah dari Rp.10.000/Hari! Temukan Keuntungan Eksklusif di Ternak Kurban!',
                'content' => "Ternak Kurban adalah solusi kurban yang memberikan keuntungan harga lebih murah bagi mudhohi (pekurban) melalui program tabungan kurban, sekaligus memberikan keuntungan tambahan bagi mitra peternak.
Beberapa keunggulan Ternak Kurban :
-. Kurban lebih murah & lebih mudah, mulai dari Rp.10.000,- /hari
-. Jaminan ternak sehat dan sesuai syariah
-. Mitra peternak terpercaya & berpengalaman
-. Akad langsung dengan mitra peternak
-. Monitoring pemeliharaan & pelacakan pengiriman
-. Gratis pengiriman ke lokasi kurban",
                'is_active' => true,
                'image' => 'ternak-kurban/mitra-peternak/_01_LogoSWS-NoBG.png',
                'order' => 1,
                'module' => 'qurban',
            ],
            [
                'slug' => 'syarat-menjadi-mitra-penyedia-ternak-kurban',
                'title' => 'Syarat Menjadi Mitra Penyedia Ternak Kurban',
                'excerpt' => 'Ingin Jadi Mitra Penyedia Ternak Kurban? Lihat Syaratnya di Sini dan Bergabung dengan Program Terpercaya!',
                'content' => "Tidak semua peternak bisa menjadi mitra tabungan kurban. Selain berkewajiban memenuhi target kurban yang ditetapkan dan memberikan ternak pengganti apabila terjadi kematian atau cacat, mitra peternak juga harus memenuhi syarat :
-. Peternak memiliki kandang sendiri (bukan sewa) dengan fasilitas pemeliharaan ternak yang layak
-. Peternak memiliki pengalaman beternak minimal 7 tahun untuk sapi / kerbau, atau minimal 5 tahun untuk domba / kambing
-. Peternak dikenal bertanggungjawab baik terhadap pelanggan maupun antar sesama peternak dan pihak lainnya
-. Peternak wajib mematuhi aturan yang disepakati, termasuk melakukan pencatatan dan pelaporan secara berkala",
                'is_active' => true,
                'image' => 'ternak-kurban/mitra-peternak/_02_LogoBMP-NoBG.png',
                'order' => 1,
                'module' => 'qurban',
            ],
            [
                'slug' => 'ketentuan-tabungan-kurban',
                'title' => 'Ketentuan Tabungan Kurban',
                'excerpt' => 'Tabungan Kurban: Mulai dari Rabiul Awal Hingga Pengiriman Gratis! Simak Ketentuan Lengkapnya!',
                'content' => "-. Masa tabungan 10 bulan, dimulai Rabiul Awal
-. Akad dengan mitra peternak mulai bulan ke-6 sampai ke-8
-. Potongan harga menyesuaikan dengan waktu akad
-. Jaminan penggantian hewan kurban oleh peternak apabila ternak cacat atau mati
-. Tabungan dapat dilanjutkan untuk kurban tahun berikutnya
Adapun proses yang dijalankan pada program tabungan kurban adalah sebagai berikut :
1. Mudhohi (Pekurban) melakukan registrasi dan menetapkan target kurban
2. Meakukan setoran tabungan atas nama mudhohi (pekurban)
3. Pada bulan ke-6 atau ketika tabungan mencapai 50% dari target kurban, mudhohi melakukan akad dengan mitra peternak
4. Mitra peternak menyediakan hewan kurban untuk memenuhi target yang ditetapkan mudhohi (pekurban)
5. Menjelang pelaksanaan kurban, mitra peternak melakukan penimbangan sebagai dasar bagi mudhohi (pekurban) melakukan pelunasan
6. Pada jadwal yang sudah ditentukan, mitra peternak mengirimkan hewan kurban dan mudhohi (pekurban) dapat melacak pengirimannya secara online
7. Hewan kurban diterima oleh mudhohi (pekurban) atau pihak yang mewakilinya",
                'is_active' => true,
                'image' => 'ternak-kurban/mitra-peternak/_03_LogoSHL-NoBG.png',
                'order' => 1,
                'module' => 'qurban',
            ],
            [
                'slug' => 'jenis-jenis-ternak-kurban',
                'title' => 'Jenis-jenis Ternak Kurban',
                'excerpt' => 'Hewan Kurban Harus Sehat dan Sesuai Syariah! Ketahui Jenis dan Syaratnya di Sini!',
                'content' => "Hewan ternak yang akan dijadikan kurban harus dalam kondisi sehat dan tidak cacat. Beberapa kondisi yang harus dihindari antara lain:
- Hewan buta, baik salah satu atau kedua matanya.
- Hewan pincang yang menyebabkan tidak bisa berjalan normal.
- Hewan yang sangat kurus sehingga tidak memiliki sumsum tulang yang cukup.
- Hewan yang sakit parah.

Meskipun tidak ada ketentuan khusus mengenai jenis kelamin kambing yang akan dikurbankan, baik jantan maupun betina dapat dijadikan hewan kurban selama memenuhi syarat usia dan kondisi kesehatan.
-. Sapi / Kerbau minimal berumur 2 tahun dan telah masuk tahun ke-3
-. Domba berumur 1 tahun, atau minimal berumur 6 bulan bagi yang sulit mendapatkan domba yang berumur 1 tahun
-. Kambing minimal berumur 1 tahun dan telah masuk tahun ke 2",
                'is_active' => true,
                'image' => 'ternak-kurban/hewan-kurban/DombaAwassi.png',
                'order' => 1,
                'module' => 'qurban',
            ],
            [
                'slug' => 'perbedaan-berat-faktur-berat-timbang',
                'title' => 'Perbedaan Berat Faktur & Berat Timbang',
                'excerpt' => 'Pahami Perbedaan Berat Faktur vs Berat Timbang dalam Program Tabungan Kurban! Klik untuk Info Lengkap!',
                'content' => "Berat Faktur :
Penimbangan dilakukan di daerah asal ternak, biasanya akan mengalami penyusutan berat karena pengiriman (misal : Kupang/Bali ke Bandung, susut ± 20% - 30%; Madura/Jawa Timur ke Bandung, susut ± 15% - 20%) sehingga umumnya harga jual lebih murah.
Berat Timbang :
Penimbangan dilakukan dikandang peternak saat menjelang kurban sehingga nilai yang dibayarkan sesuai dengan bobot ternak, relatif tidak ada penyusutan berat

Program tabungan kurban menggunakan berat penimbangan yang dilakukan antara 10 hari sampai dengan 3 hari menjelang pengiriman kepada pelanggan",
                'is_active' => true,
                'image' => 'ternak-kurban/hewan-kurban/DombaDorper.png',
                'order' => 1,
                'module' => 'qurban',
            ],
        ]);
    }
}

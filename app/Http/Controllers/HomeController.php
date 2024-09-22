<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Data tentang "Tentang Kami"
        $tentangKami = [
            'judul' => 'Tentang Kami',
            'deskripsi' => 'PT. Utama Niaga Informasi adalah perusahaan yang bergerak di bidang pengembangan software, web, dan aplikasi, yang didedikasikan untuk membantu peternak dan koperasi di seluruh Indonesia.',
            'cards' => [
                [
                    'img' => 'home/assets/img/peternak_sapi.png',
                    'content' => 'Kami berkomitmen untuk memajukan teknologi di bidang peternakan, dengan tujuan akhir untuk menciptakan Indonesia yang unggul.'
                ],
                [
                    'img' => 'home/assets/img/peternak_sapi_2.png',
                    'content' => 'Kami percaya bahwa dengan teknologi yang tepat, sektor peternakan di Indonesia dapat berkembang pesat.'
                ]
                ],
            'deskripsi_2' => 'Kami berkomitmen untuk memajukan teknologi di bidang peternakan, dengan tujuan akhir untuk menciptakan Indonesia yang unggul. Melalui solusi digital yang inovatif, kami mendukung para peternak dalam meningkatkan efisiensi, produktivitas, dan daya saing di era teknologi ini. ',
        ];

        // Data tentang "Fitur Dari TernakKu" dengan gambar
        $fiturTernakKu = [
            [
                'text' => 'Mengelola aktivitas harian ternak, dari vaksinasi hingga penjualan.',
                'img'  => 'home/assets/img/image.png'
            ],
            [
                'text' => 'Mengelola produksi pakan, vitamin, dan kesehatan ternak.',
                'img'  => 'home/assets/img/image-1.png'
            ],
            [
                'text' => 'Menyediakan platform pemasaran dan distribusi untuk peternak.',
                'img'  => 'home/assets/img/image-2.png'
            ],
            [
                'text' => 'Laporan aktivitas peternak untuk meningkatkan efisiensi dan produksi.',
                'img'  => 'home/assets/img/image-3.png'
            ],
            [
                'text' => 'Memantau kesehatan ternak dengan sistem pelaporan otomatis.',
                'img'  => 'home/assets/img/image-4.png'
            ],
            [
                'text' => 'Membantu peternak mengakses pasar yang lebih luas untuk produk berkualitas.',
                'img'  => 'home/assets/img/image.png'
            ]
        ];

        // Kirimkan data ke view
        return view('home.home', compact('tentangKami', 'fiturTernakKu'));
    }
}

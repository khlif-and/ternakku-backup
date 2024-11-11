<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $beratDipilih = [250, 280, 310, 330, 360, 380, 420, 480];
        $hargaKurbanReguler = [
            20250000, 22400000, 24490000, 25410000,
            27000000, 27740000, 29820000, 33600000
        ];

        $harga2025 = $this->price_per_kg;
        $harga2024 = $harga2025 - 4000;

        $hargaJanuari2025 = $harga2025 * 0.93;
        $hargaHewanJanuari2025 = $hargaJanuari2025 * $beratDipilih[$this->order - 1];
        $uangMukaJanuari2025 = $hargaHewanJanuari2025 * 0.50;
        $pembayaranBulananJanuari2025 = ($hargaHewanJanuari2025 - $uangMukaJanuari2025) / 5;

        $hargaFebruari2025 = $harga2025 * 0.94;
        $hargaHewanFebruari2025 = $hargaFebruari2025 * $beratDipilih[$this->order - 1];
        $uangMukaFebruari2025 = $hargaHewanFebruari2025 * 0.60;
        $pembayaranBulananFebruari2025 = ($hargaHewanFebruari2025 - $uangMukaFebruari2025) / 4;

        return [
            'package' => [
                'id' => $this->id,
                'farm_id' => $this->farm_id,
                'order' => $this->order,
                'name' => $this->name,
                'start_weight' => $this->start_weight,
                'end_weight' => $this->end_weight,
                'price_per_kg' => $this->price_per_kg,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'formatted' => [
                'Paket Kurban' => 'SA-' . $this->order,
                'Bobot sapi' => "{$this->start_weight} - {$this->end_weight}",
                'Harga 2024 (/kg)' => number_format($harga2024, 0, ',', '.'),
                'Estimasi harga 2025 (/kg)' => number_format($harga2025, 0, ',', '.'),
                'Simulasi kurban 2025' => [
                    'Berat dipilih' => $beratDipilih[$this->order - 1],
                    'Harga kurban reguler' => number_format($hargaKurbanReguler[$this->order - 1], 0, ',', '.'),
                ],
                'Pilihan Akad' => [
                    'Januari 2025, diskon 7%' => [
                        'Harga /kg' => number_format($hargaJanuari2025, 0, ',', '.'),
                        'Harga hewan kurban' => number_format($hargaHewanJanuari2025, 0, ',', '.'),
                        'Porsi 1/7 Harga' => number_format($hargaHewanJanuari2025 / 7, 0, ',', '.'),
                        'Uang muka pembelian 50%' => number_format($uangMukaJanuari2025, 0, ',', '.'),
                        'Porsi 1/7 Uang muka' => number_format($uangMukaJanuari2025 / 7, 0, ',', '.'),
                        'Pembayaran Bulanan' => [
                            'Februari - ≤ 03 Juni (± 5 bulan)' => number_format($pembayaranBulananJanuari2025, 0, ',', '.'),
                            'Porsi 1/7 per bulan' => number_format($pembayaranBulananJanuari2025 / 7, 0, ',', '.'),
                            'Porsi 1/7 per hari' => number_format(($pembayaranBulananJanuari2025 / 7) / 30, 0, ',', '.'),
                        ],
                    ],
                    'Februari 2025, diskon 6%' => [
                        'Harga /kg' => number_format($hargaFebruari2025, 0, ',', '.'),
                        'Harga hewan kurban' => number_format($hargaHewanFebruari2025, 0, ',', '.'),
                        'Porsi 1/7 Harga' => number_format($hargaHewanFebruari2025 / 7, 0, ',', '.'),
                        'Uang muka pembelian 60%' => number_format($uangMukaFebruari2025, 0, ',', '.'),
                        'Porsi 1/7 Uang muka' => number_format($uangMukaFebruari2025 / 7, 0, ',', '.'),
                        'Pembayaran Bulanan' => [
                            'Maret - ≤ 03 Juni (± 4 bulan)' => number_format($pembayaranBulananFebruari2025, 0, ',', '.'),
                            'Porsi 1/7 per bulan' => number_format($pembayaranBulananFebruari2025 / 7, 0, ',', '.'),
                            'Porsi 1/7 per hari' => number_format(($pembayaranBulananFebruari2025 / 7) / 30, 0, ',', '.'),
                        ],
                    ],
                ],
            ],
        ];
    }
}

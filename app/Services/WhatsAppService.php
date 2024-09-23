<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class WhatsAppService
{
    public function sendMessage($number, $message)
    {
        // Format nomor telepon sesuai kebutuhan endpoint
        $formattedNumber = $number . '@s.whatsapp.net';

        // Ambil URL dari konfigurasi
        $waUrl = config('wa.wa_url');

        try {
            // Kirim request POST menggunakan Laravel HTTP Client
            $response = Http::post($waUrl, [
                'phone' => $formattedNumber,
                'message' => $message,
            ]);

            // Periksa apakah request berhasil
            if ($response->successful()) {
                $responseData = $response->json();

                // Cek apakah kode respons adalah "SUCCESS"
                if (isset($responseData['code']) && $responseData['code'] === 'SUCCESS') {
                    return [
                        'status' => 'success',
                        'message' => $responseData['message'],
                        'data' => $responseData['results'],
                    ];
                }

                // Jika kode bukan "SUCCESS"
                return [
                    'status' => 'error',
                    'message' => $responseData['message'] ?? 'Unexpected response format',
                ];
            }

            // Tangani respon gagal dari server
            return [
                'status' => 'error',
                'message' => $response->body(),
            ];
        } catch (Exception $e) {
            // Tangani exception
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}

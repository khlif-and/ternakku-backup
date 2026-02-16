<?php

use App\Models\QurbanPrice;
use Illuminate\Support\Carbon;
use App\Enums\LivestockTypeEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

if (!function_exists('generateOtp')) {
    /**
     * Generate a six-digit OTP (One Time Password).
     *
     * This function generates a random six-digit number
     * which can be used as an OTP for user verification.
     *
     * @return int A six-digit OTP.
     */
    function generateOtp()
    {
        return rand(100000, 999999);
    }
}

if (!function_exists('getNeoObject')) {
    /**
     * Get the public URL of an object stored in the Neo bucket.
     *
     * @param string|null $fileName
     * @return string|null
     */
    function getNeoObject($fileName)
    {
        if (empty($fileName))
            return null;

        // Menggunakan Neo Object Storage (S3-compatible)
        return config('filesystems.disks.neo.endpoint') . '/' . config('filesystems.disks.neo.bucket') . '/' . $fileName;
    }
}


if (!function_exists('uploadNeoObject')) {
    /**
     * Upload a file to the Neo bucket using Laravel Storage facade.
     *
     * @param string|\Illuminate\Http\UploadedFile|\Illuminate\Http\File $file
     * @param string $fileName
     * @param string $pathName
     * @return string|null
     */
    function uploadNeoObject($file, $fileName, $pathName)
    {
        $fullName = $pathName . $fileName;

        try {
            // Ambil konten file
            if (is_string($file)) {
                $content = file_get_contents($file);
            } elseif ($file instanceof \Illuminate\Http\UploadedFile || $file instanceof \Illuminate\Http\File) {
                $content = file_get_contents($file->getRealPath());
            } else {
                Log::error('uploadNeoObject: Tipe file tidak didukung');
                return null;
            }

            // Upload ke Neo bucket menggunakan Storage facade
            Storage::disk('neo')->put($fullName, $content, 'public');

            return $fullName;
        } catch (\Exception $e) {
            Log::error('Neo Upload Error: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('deleteNeoObject')) {
    /**
     * Delete a file from the Neo bucket using Laravel Storage facade.
     *
     * @param string $fileName
     * @return void
     */
    function deleteNeoObject($fileName)
    {
        try {
            Storage::disk('neo')->delete($fileName);
        } catch (\Exception $e) {
            Log::error('Neo Delete Error: ' . $e->getMessage());
        }
    }
}

if (!function_exists('getInseminationCycleDate')) {
    function getInseminationCycleDate($livestockTypeId, string $startDate): string
    {
        $tglTransaksi = Carbon::parse($startDate);

        switch ($livestockTypeId) {
            case LivestockTypeEnum::SAPI->value:
            case LivestockTypeEnum::KERBAU->value:
                $tglSiklus = $tglTransaksi->addDays(21)->format('Y-m-d');
                break;
            case LivestockTypeEnum::DOMBA->value:
                $tglSiklus = $tglTransaksi->addDays(16)->format('Y-m-d');
                break;
            case LivestockTypeEnum::KAMBING->value:
                $tglSiklus = $tglTransaksi->addDays(17)->format('Y-m-d');
                break;
            default:
                $tglSiklus = null;
        }

        return $tglSiklus;
    }
}

if (!function_exists('getEstimatedBirthDate')) {
    function getEstimatedBirthDate($livestockTypeId, string $transactionDate, int $pregnantAge = null): ?string
    {
        // Konversi tanggal transaksi ke objek Carbon
        $tglTransaksi = Carbon::parse($transactionDate);

        // Jika usia bunting diberikan, hitung usia bunting dalam hari
        $usiaBuntingHari = $pregnantAge ? $pregnantAge * 30 : 0;

        switch ($livestockTypeId) {
            case LivestockTypeEnum::DOMBA->value: // Domba
            case LivestockTypeEnum::KAMBING->value: // Kambing
                // Tambahkan 150 hari - usia bunting (dalam hari)
                $tglEstimasi = $tglTransaksi->addDays(150 - $usiaBuntingHari)->format('Y-m-d');
                break;
            case LivestockTypeEnum::SAPI->value:
            case LivestockTypeEnum::KERBAU->value:
                // Tambahkan 280 hari - usia bunting (dalam hari)
                $tglEstimasi = $tglTransaksi->addDays(280 - $usiaBuntingHari)->format('Y-m-d');
                break;
            default:
                $tglEstimasi = null;
        }

        return $tglEstimasi;
    }
}

if (!function_exists('getEstimationQurbanPrice')) {
    function getEstimationQurbanPrice($farmId, $livestockTypeId, $weight, $hijriYear = 1446)
    {
        $price = QurbanPrice::where('farm_id', $farmId)
            ->where('livestock_type_id', $livestockTypeId)
            ->where('hijri_year', $hijriYear)
            ->where('start_weight', '<=', $weight)
            ->where('end_weight', '>=', $weight)
            ->orderBy('start_weight')
            ->first();

        return $price ? $price->price_per_kg * $weight : null;
    }
}
<?php

use Aws\S3\S3Client;
use App\Models\QurbanPrice;
use Illuminate\Support\Carbon;
use App\Enums\LivestockTypeEnum;
use Aws\S3\Exception\S3Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

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
    function getNeoObject($fileName)
    {
        return config('filesystems.disks.neo.endpoint') . '/' . config('filesystems.disks.neo.bucket') . '/' . $fileName;
    }
}

if (!function_exists('uploadNeoObject')) {
    function uploadNeoObject($file, $fileName, $pathName)
    {
        $client = new S3Client([
            'version'     => 'latest',
            'region'      => config('filesystems.disks.neo.region'),
            'endpoint'    => config('filesystems.disks.neo.endpoint'),
            'credentials' => [
                'key'    => config('filesystems.disks.neo.key'),
                'secret' => config('filesystems.disks.neo.secret'),
            ],
        ]);

        try {
            $fullName = $pathName . $fileName;

            // Tentukan ContentType dan SourceFile
            if (is_string($file)) {
                // Jika file adalah path string
                $contentType = File::mimeType($file);
                $sourceFile = $file;
            } elseif ($file instanceof \Illuminate\Http\UploadedFile || $file instanceof \Illuminate\Http\File) {
                // Jika file adalah UploadedFile / File instance
                $contentType = $file->getClientMimeType();
                $sourceFile = $file->getRealPath();
            } else {
                throw new \Exception('uploadNeoObject hanya mendukung string path atau instance File/UploadedFile');
            }

            $client->putObject([
                'Bucket'      => config('filesystems.disks.neo.bucket'),
                'Key'         => $fullName,
                'ContentType' => $contentType,
                'SourceFile'  => $sourceFile,
                'ACL'         => 'public-read',
            ]);

            return $fullName;

        } catch (S3Exception $e) {
            Log::error('S3 Upload Error: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('deleteNeoObject')) {
    function deleteNeoObject($fileName)
    {
        $client = new S3Client([
            'version'     => 'latest',
            'region'      => config('filesystems.disks.neo.region'),
            'endpoint'    => config('filesystems.disks.neo.endpoint'),
            'credentials' => [
                'key'    => config('filesystems.disks.neo.key'),
                'secret' => config('filesystems.disks.neo.secret'),
            ],
        ]);

        try {
            $client->deleteObject([
                'Bucket' => config('filesystems.disks.neo.bucket'),
                'Key'    => $fileName,
            ]);

        } catch (S3Exception $e) {
            \Log::error('S3 Deletion Error: ' . $e->getMessage());
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
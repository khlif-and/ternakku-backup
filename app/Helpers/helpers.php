<?php

use Aws\S3\S3Client;

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

            $client->putObject([
                'Bucket'      => config('filesystems.disks.neo.bucket'),
                'Key'         => $fullName,
                'ContentType' => $file->getClientMimeType(),
                'SourceFile'  => $file->getRealPath(),
                'ACL'         => 'public-read',
            ]);

            return $fullName;

        } catch (S3Exception $e) {
            // Tangani pengecualian khusus S3.
            \Log::error('S3 Upload Error: ' . $e->getMessage());
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

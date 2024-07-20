<?php

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
        return config('filesystems.disks.neo.endpoint') . config('filesystems.disks.neo.bucket') . '/' . $fileName;
    }
}

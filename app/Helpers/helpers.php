<?php

if (!function_exists('generateOtp')) {
    function generateOtp()
    {
        return rand(100000, 999999);
    }
}

<?php

namespace App\Services\Qurban;

use App\Models\QurbanDriver;


class DriverService
{
    public function getDrivers($farmId)
    {
        $customers = QurbanDriver::where('farm_id', $farmId)->get();

        return $customers;
    }
}

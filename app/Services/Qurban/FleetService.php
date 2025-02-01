<?php

namespace App\Services\Qurban;

use App\Models\QurbanFleet;


class FleetService
{
    public function getFleets($farmId)
    {
        $customers = QurbanFleet::where('farm_id', $farmId)->get();

        return $customers;
    }
}

<?php

namespace App\Services\Web\Farming\LivestockReception;

class LivestockReceptionService
{
    protected LivestockReceptionCoreService $core;

    public function __construct(LivestockReceptionCoreService $core)
    {
        $this->core = $core;
    }

    public function find($farm, $id)
    {
        return $this->core->findReception($farm, $id);
    }
}


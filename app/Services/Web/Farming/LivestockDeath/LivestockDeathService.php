<?php

namespace App\Services\Web\Farming\LivestockDeath;

class LivestockDeathService
{
    protected LivestockDeathCoreService $core;

    public function __construct(LivestockDeathCoreService $core)
    {
        $this->core = $core;
    }

    public function find($farm, $id)
    {
        return $this->core->findDeath($farm, $id);
    }

    public function store($farm, array $data)
    {
        return $this->core->storeDeath($farm, $data);
    }

    public function update($farm, $id, array $data)
    {
        return $this->core->updateDeath($farm, $id, $data);
    }

    public function delete($farm, $id)
    {
        return $this->core->deleteDeath($farm, $id);
    }
}

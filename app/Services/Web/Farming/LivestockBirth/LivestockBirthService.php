<?php

namespace App\Services\Web\Farming\LivestockBirth;

class LivestockBirthService
{
    protected LivestockBirthCoreService $core;

    public function __construct(LivestockBirthCoreService $core)
    {
        $this->core = $core;
    }

    public function find($farm, $id)
    {
        return $this->core->findBirth($farm, $id);
    }

    public function store($farm, array $data)
    {
        return $this->core->storeBirth($farm, $data);
    }

    public function update($farm, $id, array $data)
    {
        return $this->core->updateBirth($farm, $id, $data);
    }

    public function delete($farm, $id)
    {
        return $this->core->deleteBirth($farm, $id);
    }
}

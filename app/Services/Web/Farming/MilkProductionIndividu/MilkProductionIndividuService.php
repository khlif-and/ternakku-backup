<?php

namespace App\Services\Web\Farming\MilkProductionIndividu;

class MilkProductionIndividuService
{
    protected MilkProductionIndividuCoreService $core;

    public function __construct(MilkProductionIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function find($farm, $id)
    {
        return $this->core->find($farm, $id);
    }

    public function store($farm, array $data)
    {
        return $this->core->store($farm, $data);
    }

    public function update($farm, $id, array $data)
    {
        return $this->core->update($farm, $id, $data);
    }

    public function delete($farm, $id)
    {
        return $this->core->delete($farm, $id);
    }
}

<?php

namespace App\Services\Web\Farming\NaturalInsemination;

class NaturalInseminationService
{
    protected NaturalInseminationCoreService $core;

    public function __construct(NaturalInseminationCoreService $core)
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

    public function delete($item)
    {
        return $this->core->delete($item);
    }
}
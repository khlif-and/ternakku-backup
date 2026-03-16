<?php

namespace App\Services\Web\Farming\MutationIndividu;

class MutationIndividuService
{
    protected MutationIndividuCoreService $core;

    public function __construct(MutationIndividuCoreService $core)
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

    public function checkIsLatest($mutationIndividu)
    {
        return $this->core->checkIsLatest($mutationIndividu);
    }
}

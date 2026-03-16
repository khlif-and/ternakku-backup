<?php

namespace App\Services\Web\Farming\FeedingColony;

class FeedingIndividuService
{
    protected FeedingIndividuCoreService $core;

    public function __construct(FeedingIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function findFeedingIndividu($farm, $id)
    {
        return $this->core->find($farm, $id);
    }

    public function storeFeedingIndividu($farm, array $data)
    {
        return $this->core->store($farm, $data);
    }

    public function updateFeedingIndividu($farm, $id, array $data)
    {
        return $this->core->update($farm, $id, $data);
    }

    public function deleteFeedingIndividu($farm, $id): void
    {
        $this->core->delete($farm, $id);
    }
}

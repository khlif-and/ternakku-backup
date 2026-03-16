<?php

namespace App\Services\Web\Farming\FeedingColony;

class FeedingColonyService
{
    protected FeedingColonyCoreService $core;

    public function __construct(FeedingColonyCoreService $core)
    {
        $this->core = $core;
    }

    public function findFeedingColony($farm, $id)
    {
        return $this->core->find($farm, $id);
    }
}

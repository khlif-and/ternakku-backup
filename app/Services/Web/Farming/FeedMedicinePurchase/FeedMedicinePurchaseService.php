<?php

namespace App\Services\Web\Farming\FeedMedicinePurchase;

class FeedMedicinePurchaseService
{
    protected FeedMedicinePurchaseCoreService $core;

    public function __construct(FeedMedicinePurchaseCoreService $core)
    {
        $this->core = $core;
    }

    public function find($farm, $id)
    {
        return $this->core->findPurchase($farm, $id);
    }

    public function store($farm, array $data)
    {
        return $this->core->storePurchase($farm, $data);
    }

    public function update($farm, $id, array $data)
    {
        return $this->core->updatePurchase($farm, $id, $data);
    }

    public function delete($farm, $id)
    {
        return $this->core->deletePurchase($farm, $id);
    }
}

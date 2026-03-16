<?php

namespace App\Services\Web\Qurban\QurbanDelivery;

class QurbanDeliveryService
{
    protected QurbanDeliveryCoreService $core;

    public function __construct(QurbanDeliveryCoreService $core)
    {
        $this->core = $core;
    }

    public function find($id)
    {
        return $this->core->find($id);
    }

    public function store(int $farmId, array $data)
    {
        return $this->core->store($farmId, $data);
    }

    public function setReadyToDeliver(int $farmId, $id)
    {
        return $this->core->setReadyToDeliver($farmId, $id);
    }

    public function delete(int $farmId, $id)
    {
        return $this->core->delete($farmId, $id);
    }
}

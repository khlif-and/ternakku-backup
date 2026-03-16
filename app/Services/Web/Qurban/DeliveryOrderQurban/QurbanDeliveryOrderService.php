<?php

namespace App\Services\Web\Qurban\DeliveryOrderQurban;

class QurbanDeliveryOrderService
{
    protected QurbanDeliveryOrderCoreService $core;

    public function __construct(QurbanDeliveryOrderCoreService $core)
    {
        $this->core = $core;
    }

    public function find($id)
    {
        return $this->core->find($id);
    }

    public function store(array $data)
    {
        return $this->core->store($data);
    }

    public function updateSchedule(int $farmId, $id, $schedule)
    {
        return $this->core->updateSchedule($farmId, $id, $schedule);
    }

    public function delete(int $farmId, $id)
    {
        return $this->core->delete($farmId, $id);
    }
}

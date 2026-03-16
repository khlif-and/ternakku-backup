<?php

namespace App\Services\Web\Qurban\LivestockDeliveryQurban;

class LivestockDeliveryNoteService
{
    protected LivestockDeliveryNoteCoreService $core;

    public function __construct(LivestockDeliveryNoteCoreService $core)
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

    public function updateSchedule(int $farmId, $id, $schedule)
    {
        return $this->core->updateSchedule($farmId, $id, $schedule);
    }

    public function delete(int $farmId, $id)
    {
        return $this->core->delete($farmId, $id);
    }
}

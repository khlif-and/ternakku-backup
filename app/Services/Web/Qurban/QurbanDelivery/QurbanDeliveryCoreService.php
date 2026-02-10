<?php

namespace App\Services\Web\Qurban\QurbanDelivery;

use App\Services\Qurban\DeliveryInstructionService;
use App\Models\QurbanDeliveryInstructionH;
use Illuminate\Pagination\LengthAwarePaginator;

class QurbanDeliveryCoreService
{
    protected $apiService;

    public function __construct(DeliveryInstructionService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function listDeliveries(int $farmId, array $filters): LengthAwarePaginator
    {
        $params = [
            'delivery_date_start' => $filters['start_date'] ?? null,
            'delivery_date_end' => $filters['end_date'] ?? null,
            'status' => $filters['status'] ?? null,
            'driver_id' => $filters['driver_id'] ?? null,
            'fleet_id' => $filters['fleet_id'] ?? null,
        ];

        $deliveryInstructions = $this->apiService->getDeliveryInstructions($farmId, $params);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $filters['per_page'] ?? 10;
        $currentItems = $deliveryInstructions->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

        return new LengthAwarePaginator($currentItems, $deliveryInstructions->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }

    public function store(int $farmId, array $data)
    {
        return $this->apiService->storeDeliveryInstruction($farmId, [
            'delivery_date' => $data['delivery_date'],
            'driver_id' => $data['driver_id'],
            'fleet_id' => $data['fleet_id'],
            'delivery_order_ids' => $data['delivery_order_ids'],
        ]);
    }

    public function find($id)
    {
        return QurbanDeliveryInstructionH::with([
            'driver',
            'fleet',
            'farm',
            'qurbanDeliveryInstructionD.qurbanDeliveryOrderH.qurbanCustomerAddress.qurbanCustomer.user',
            'qurbanDeliveryInstructionD.qurbanDeliveryOrderH.qurbanDeliveryOrderD.livestock.livestockBreed',
        ])->findOrFail($id);
    }

    public function setReadyToDeliver(int $farmId, $id)
    {
        return $this->apiService->setToReadyToDeliver($farmId, $id);
    }

    public function delete(int $farmId, $id)
    {
        return $this->apiService->deleteDeliveryInstruction($farmId, $id);
    }
}

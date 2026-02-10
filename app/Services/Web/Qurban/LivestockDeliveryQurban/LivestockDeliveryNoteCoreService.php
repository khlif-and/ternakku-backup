<?php

namespace App\Services\Web\Qurban\LivestockDeliveryQurban;

use App\Services\Qurban\DeliveryOrderService;
use App\Models\QurbanDeliveryOrderH;
use Illuminate\Pagination\LengthAwarePaginator;

class LivestockDeliveryNoteCoreService
{
    protected DeliveryOrderService $apiService;

    public function __construct(DeliveryOrderService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function listDeliveryNotes(int $farmId, array $filters): LengthAwarePaginator
    {
        $params = [
            'transaction_date_start' => $filters['start_date'] ?? null,
            'transaction_date_end' => $filters['end_date'] ?? null,
            'qurban_customer_id' => $filters['qurban_customer_id'] ?? null,
            'status' => $filters['status'] ?? null,
        ];

        $deliveryOrders = $this->apiService->getDeliveryOrders($farmId, $params);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $filters['per_page'] ?? 10;
        $currentItems = $deliveryOrders->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

        return new LengthAwarePaginator($currentItems, $deliveryOrders->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }

    public function store(int $farmId, array $data)
    {
        return $this->apiService->storeDeliveryOrder($farmId, [
            'qurban_sales_livestock_id' => $data['qurban_sales_livestock_id'],
            'transaction_date' => $data['transaction_date'],
        ]);
    }

    public function find($id)
    {
        return QurbanDeliveryOrderH::with([
            'qurbanCustomerAddress.qurbanCustomer.user',
            'qurbanSaleLivestockH.qurbanCustomer.user',
            'qurbanDeliveryOrderD.livestock.livestockBreed',
            'qurbanDeliveryOrderD.livestock.livestockType',
            'farm',
        ])->findOrFail($id);
    }

    public function updateSchedule(int $farmId, $id, $schedule)
    {
        return $this->apiService->setDeliverySchedule($farmId, $id, $schedule);
    }

    public function delete(int $farmId, $id)
    {
        return $this->apiService->deleteDeliveryOrder($farmId, $id);
    }
}

<?php

namespace App\Services\Web\Qurban\DeliveryOrderQurban;

use App\Services\Qurban\DeliveryOrderService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\QurbanDeliveryOrderH;

class QurbanDeliveryOrderCoreService
{
    protected $apiService;

    public function __construct(DeliveryOrderService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function listDeliveries(int $farmId, array $filters): LengthAwarePaginator
    {
        $params = [
            'transaction_date_start' => $filters['start_date'] ?? null,
            'transaction_date_end' => $filters['end_date'] ?? null,
            'qurban_customer_id' => $filters['qurban_customer_id'] ?? null,
            'page' => $filters['page'] ?? 1,
            'per_page' => $filters['per_page'] ?? 10,
        ];

        $deliveryOrders = $this->apiService->getDeliveryOrders($farmId, $params);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $params['per_page'];
        $currentItems = $deliveryOrders->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

        return new LengthAwarePaginator($currentItems, $deliveryOrders->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }

    public function store(array $data)
    {
        // Call API Service directly, passing raw parameters as expected
        return $this->apiService->storeDeliveryOrder($data['farm_id'] ?? null, [
            'qurban_sales_livestock_id' => $data['qurban_sales_livestock_id'],
            'transaction_date' => $data['transaction_date'],
        ]);
    }

    public function find($id)
    {
        // Utilizing API Service logic if possible, or direct model if API service requires farm_id which we might not have in scope here easily without query, 
        // but API controller uses getById($farm_id, $id).
        // For simplicity and matching API "read" logic, we can access model directly like API service getById does, but let's stick to simple find for web helper
        return QurbanDeliveryOrderH::with(['qurbanSaleLivestockH.qurbanCustomer.user', 'qurbanDeliveryOrderD.livestock.livestockType', 'qurbanDeliveryOrderD.livestock.livestockBreed', 'farm.farmDetail'])->findOrFail($id);
    }

    public function updateSchedule($farmId, $id, $schedule)
    {
        return $this->apiService->setDeliverySchedule($farmId, $id, $schedule);
    }

    public function delete(int $farmId, $id)
    {
        return $this->apiService->deleteDeliveryOrder($farmId, $id);
    }
}
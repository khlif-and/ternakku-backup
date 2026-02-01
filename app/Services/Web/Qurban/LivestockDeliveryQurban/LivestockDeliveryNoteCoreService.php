<?php

namespace App\Services\Web\Qurban\LivestockDeliveryQurban;

use App\Services\Qurban\DeliveryOrderService;
use App\Models\QurbanDeliveryOrderH;

class LivestockDeliveryNoteCoreService
{
    protected DeliveryOrderService $apiService;

    public function __construct(DeliveryOrderService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function listDeliveryNotes(array $filters)
    {
        $query = QurbanDeliveryOrderH::with(['qurbanSaleLivestockH.qurbanCustomer.user', 'qurbanDeliveryOrderD.livestock']);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['qurban_customer_id'])) {
            $query->whereHas('qurbanSaleLivestockH', function ($q) use ($filters) {
                $q->where('qurban_customer_id', $filters['qurban_customer_id']);
            });
        }

        return $query->latest()->paginate(10);
    }

    public function store(array $data)
    {
        // $data expects 'farm_id', 'qurban_sales_livestock_id', 'transaction_date'
        $result = $this->apiService->storeDeliveryOrder($data['farm_id'], $data);

        if ($result['error']) {
            throw new \Exception("Gagal membuat surat jalan melalui API Service");
        }

        // Return the first created order (or collection if needed, but UI typically expects single object redirect)
        // Store API returns array of objects.
        return $result['data'][0] ?? null;
    }

    public function find($id)
    {
        return QurbanDeliveryOrderH::with(['qurbanSaleLivestockH.qurbanCustomer.user', 'qurbanDeliveryOrderD.livestock'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        // The API only has setDeliverySchedule or logic inside store. 
        // For simple updates (like date), we can do it directly or verify if API has update endpoint.
        // API controller show setDeliverySchedule.
        // Let's stick to basic model update for date if API service doesn't have generic update.
        // Or if we want to be strict, we only allow what API allows. 
        // user asked to "sesuaikan", so if API doesn't support full update, we shouldn't either?
        // But for "Edit" feature in web, changing date is reasonable.

        $deliveryOrder = QurbanDeliveryOrderH::findOrFail($id);
        $deliveryOrder->update([
            'transaction_date' => $data['delivery_date']
        ]);

        return $deliveryOrder;
    }

    public function delete($id): void
    {
        // Need farm_id. Fetch from model.
        $deliveryOrder = QurbanDeliveryOrderH::findOrFail($id);
        $result = $this->apiService->deleteDeliveryOrder($deliveryOrder->farm_id, $id);

        if ($result['error']) {
            throw new \Exception("Gagal menghapus surat jalan melalui API Service");
        }
    }
}

<?php

namespace App\Services\Web\Qurban\QurbanDelivery;

use App\Services\Qurban\DeliveryOrderService;
use Illuminate\Pagination\LengthAwarePaginator;

class QurbanDeliveryCoreService
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
        $result = $this->apiService->storeDeliveryOrder($data['farm_id'] ?? null, [
            'qurban_sales_livestock_id' => $data['qurban_sales_livestock_id'],
            'transaction_date' => $data['transaction_date'],
        ]);

        if (empty($result['error']) && !empty($result['data'])) {
            foreach ($result['data'] as $deliveryOrder) {
                $deliveryOrder->load([
                    'farm.farmDetail.region',
                    'qurbanSaleLivestockH.qurbanCustomer.user',
                    'qurbanCustomerAddress',
                    'qurbanDeliveryOrderD.livestock.livestockType',
                    'qurbanDeliveryOrderD.livestock.livestockBreed',
                    'qurbanDeliveryOrderD.livestock.livestockSex',
                    'qurbanDeliveryOrderD.livestock.qurbanSaleLivestockD.qurbanCustomerAddress'
                ]);

                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.qurban_delivery_order', [
                    'deliveryOrder' => $deliveryOrder,
                ]);

                $fileName = now()->format('YmdHis') . '-delivery-order-' . $deliveryOrder->id . '.pdf';
                $tempPath = storage_path('app/temp/' . $fileName);

                if (!\Illuminate\Support\Facades\File::exists(dirname($tempPath))) {
                    \Illuminate\Support\Facades\File::makeDirectory(dirname($tempPath), 0755, true);
                }

                $pdf->save($tempPath);

                $s3Path = 'qurban/delivery_orders/';
                $s3Url = uploadNeoObject($tempPath, $fileName, $s3Path);

                $deliveryOrder->file = $s3Url;
                $deliveryOrder->save();

                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
            }
        }

        return $result;
    }

    public function find($id)
    {

        return \App\Models\QurbanDeliveryOrderH::with(['qurbanSaleLivestockH.qurbanCustomer.user', 'qurbanDeliveryOrderD.livestock'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $deliveryOrder = \App\Models\QurbanDeliveryOrderH::findOrFail($id);
        $deliveryOrder->transaction_date = $data['transaction_date'];
        $deliveryOrder->save();

        $deliveryOrder->load([
            'farm.farmDetail.region',
            'qurbanSaleLivestockH.qurbanCustomer.user',
            'qurbanCustomerAddress',
            'qurbanDeliveryOrderD.livestock.livestockType',
            'qurbanDeliveryOrderD.livestock.livestockBreed',
            'qurbanDeliveryOrderD.livestock.livestockSex',
            'qurbanDeliveryOrderD.livestock.qurbanSaleLivestockD.qurbanCustomerAddress'
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.qurban_delivery_order', [
            'deliveryOrder' => $deliveryOrder,
        ]);

        $fileName = now()->format('YmdHis') . '-delivery-order-' . $deliveryOrder->id . '.pdf';
        $tempPath = storage_path('app/temp/' . $fileName);

        if (!\Illuminate\Support\Facades\File::exists(dirname($tempPath))) {
            \Illuminate\Support\Facades\File::makeDirectory(dirname($tempPath), 0755, true);
        }

        $pdf->save($tempPath);

        $s3Path = 'qurban/delivery_orders/';
        $s3Url = uploadNeoObject($tempPath, $fileName, $s3Path);

        $deliveryOrder->file = $s3Url;
        $deliveryOrder->save();

        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        return $deliveryOrder;
    }

    public function delete(int $farmId, $id)
    {
        return $this->apiService->deleteDeliveryOrder($farmId, $id);
    }
}

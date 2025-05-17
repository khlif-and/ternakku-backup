<?php

namespace App\Services\Qurban;

use App\Models\Farm;
use App\Models\Livestock;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Models\QurbanDeliveryOrderD;
use App\Models\QurbanDeliveryOrderH;
use App\Models\QurbanSaleLivestockD;
use App\Models\QurbanSaleLivestockH;
use App\Models\QurbanCustomerAddress;


class DeliveryOrderService
{
    public function storeDeliveryOrder($farm_id, $request)
    {
        $data = [];
        $error = false;

        DB::beginTransaction();

        try {
            $saleLivestock = QurbanSaleLivestockH::where('farm_id', $farm_id)
                ->findOrFail($request['qurban_sales_livestock_id']);

            $groupedDetails = $saleLivestock->qurbanSaleLivestockD
                ->groupBy('qurban_customer_address_id');


            foreach ($groupedDetails as $customerAddressId => $details) {

                // Buat surat jalan (Header)
                $deliveryOrder = QurbanDeliveryOrderH::create([
                    'farm_id' => $saleLivestock->farm_id,
                    'transaction_date' => $request['transaction_date'],
                    'qurban_customer_address_id' => $customerAddressId,
                    'qurban_sale_livestock_h_id' => $saleLivestock->id,
                ]);

                // Tambahkan detail berdasarkan livestock_id
                foreach ($details as $detail) {
                    QurbanDeliveryOrderD::create([
                        'qurban_delivery_order_h_id' => $deliveryOrder->id,
                        'livestock_id' => $detail->livestock_id,
                    ]);
                }

                // Simpan ke array data
                $data[] = $deliveryOrder;
            }

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $error = true;

            // Jika ingin logging lebih baik:
            \Log::error('Gagal membuat surat jalan: ' . $e->getMessage());
        }

        return [
            'data' => $data,
            'error' => $error,
        ];
    }

    public function getDeliveryOrders($farmId, $param)
    {
        $query = QurbanDeliveryOrderH::with(['qurbanDeliveryOrderD', 'qurbanCustomerAddress.qurbanCustomer', 'qurbanSaleLivestockH', 'farm'])
            ->where('farm_id', $farmId);

        // Date range filter
        $query->when($param['transaction_date_start'] ?? null, function ($q, $start) {
            $q->whereDate('transaction_date', '>=', $start);
        });

        $query->when($param['transaction_date_end'] ?? null, function ($q, $end) {
            $q->whereDate('transaction_date', '<=', $end);
        });

        $query->when($param['qurban_customer_address_id'] ?? null, function ($q, $id) {
            $q->where('qurban_customer_address_id', $id);
        });

        $query->when($param['qurban_customer_id'] ?? null, function ($q, $customerId) {
            $q->whereHas('qurbanCustomerAddress.qurbanCustomer', function ($q2) use ($customerId) {
                $q2->where('id', $customerId);
            });
        });

        $query->when($param['qurban_customer_name'] ?? null, function ($q, $name) {
            $q->whereHas('qurbanCustomerAddress.qurbanCustomer', function ($q2) use ($name) {
                $q2->where('name', 'like', '%' . $name . '%');
            });
        });

        return $query->get();
    }

    // public function getAvailableLivestock($farmId)
    // {
    //     $farm = Farm::findOrFail($farmId);

    //     // Ambil semua livestock yang statusnya 'HIDUP'
    //     $livestocks = $farm->livestocks()->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)->get();

    //     // Ambil ID dari sales orders yang sudah ada
    //     $DeliveryOrderIds = QurbanSaleLivestockD::whereIn('livestock_id', $livestocks->pluck('id'))->pluck('livestock_id');

    //     // Filter livestock yang tidak ada dalam sales orders
    //     $livestockAvailable = $livestocks->whereNotIn('id', $DeliveryOrderIds);

    //     return $livestockAvailable;
    // }

    // public function getDeliveryOrders($farmId, $param)
    // {
    //     $query = QurbanSaleLivestockH::where('farm_id', $farmId)->filterMarketing($farmId);

    //     if (!empty($param['qurban_customer_id'])) {
    //         $query->where('qurban_customer_id', $param->qurban_customer_id);
    //     }

    //     return $query->get();
    // }

    // public function getDeliveryOrder($farmId , $DeliveryOrderId)
    // {
    //     $DeliveryOrder = QurbanSaleLivestockH::where('farm_id', $farmId)->where('id' , $DeliveryOrderId)->first();

    //     return $DeliveryOrder;
    // }

    // public function updateDeliveryOrder($farmId, $id, $request)
    // {
    //     $error = false;
    //     $data = null;

    //     DB::beginTransaction();

    //     try {
    //         $header = QurbanSaleLivestockH::where('farm_id' , $farmId)->where('id' , $id)->first();

    //         $header->update([
    //             'qurban_customer_id' => $request['customer_id'],
    //             'customer_id' => $request['sales_order_id'] ?? null,
    //             'transaction_date' => $request['transaction_date']
    //         ]);

    //         $header->qurbanSaleLivestockD()->delete();

    //         foreach ($request['details'] as $item) {
    //             QurbanCustomerAddress::where('qurban_customer_id' , $request['customer_id'])->where('id' , $item['customer_address_id'])->firstOrFail();

    //             Livestock::where('farm_id' , $farmId)->where('id' , $item['livestock_id'])->where('livestock_status_id' , LivestockStatusEnum::HIDUP->value)->firstOrFail();

    //             QurbanSaleLivestockD::create([
    //                 'qurban_sale_livestock_h_id' => $header->id,
    //                 'qurban_customer_address_id' => $item['customer_address_id'],
    //                 'livestock_id' => $item['livestock_id'],
    //                 'weight' => $item['weight'],
    //                 'price_per_kg' => $item['price_per_kg'],
    //                 'price_per_head' => $item['price_per_head'],
    //                 'delivery_plan_date' => $item['delivery_plan_date'],
    //             ]);
    //         }

    //         DB::commit();

    //         $data = $header;

    //     } catch (\Exception $e) {
    //         dd($e);
    //         // Rollback transaksi jika terjadi kesalahan
    //         DB::rollBack();

    //         $error = true;
    //     }

    //     return [
    //         'data' => $data,
    //         'error' => $error
    //     ];
    // }

    // public function deleteDeliveryOrder($farmId, $id)
    // {
    //     $error = false;

    //     try {
    //         $header = QurbanSaleLivestockH::where('farm_id' , $farmId)->where('id' , $id)->first();

    //         $header->qurbanSaleLivestockD()->delete();

    //         $header->delete();

    //         // Commit transaksi
    //         DB::commit();


    //     } catch (\Exception $e) {
    //         dd($e);
    //         // Rollback transaksi jika terjadi kesalahan
    //         DB::rollBack();

    //         $error = true;
    //     }

    //     return [
    //         'error' => $error
    //     ];
    // }

}

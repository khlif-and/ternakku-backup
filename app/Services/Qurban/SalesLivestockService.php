<?php

namespace App\Services\Qurban;

use App\Models\Farm;
use App\Models\Livestock;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Models\QurbanSaleLivestockD;
use App\Models\QurbanSaleLivestockH;
use App\Models\QurbanCustomerAddress;


class SalesLivestockService
{
    public function getAvailableLivestock($farmId)
    {
        $farm = Farm::findOrFail($farmId);

        // Ambil semua livestock yang statusnya 'HIDUP'
        $livestocks = $farm->livestocks()->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)->get();

        // Ambil ID dari sales orders yang sudah ada
        $salesLivestockIds = QurbanSaleLivestockD::whereIn('livestock_id', $livestocks->pluck('id'))->pluck('livestock_id');

        // Filter livestock yang tidak ada dalam sales orders
        $livestockAvailable = $livestocks->whereNotIn('id', $salesLivestockIds);

        return $livestockAvailable;
    }

    public function getSalesLivestocks($farmId, $param)
    {
        $query = QurbanSaleLivestockH::where('farm_id', $farmId);

        if (!empty($param['qurban_customer_id'])) {
            $query->where('qurban_customer_id', $param->qurban_customer_id);
        }
    
        return $query->get();    
    }

    public function getSalesLivestock($farmId , $salesLivestockId)
    {
        $salesLivestock = QurbanSaleLivestockH::where('farm_id', $farmId)->where('id' , $salesLivestockId)->first();

        return $salesLivestock;
    }

    public function storeSalesLivestock($farm_id, $request)
    {
        $data = null;
        $error = false;

        DB::beginTransaction();

        try {
            $header = QurbanSaleLivestockH::create([
                'farm_id' => $farm_id,
                'qurban_customer_id' => $request['customer_id'],
                'customer_id' => $request['sales_order_id'] ?? null,
                'transaction_date' => $request['transaction_date']
            ]);

            foreach ($request['details'] as $item) {

                QurbanCustomerAddress::where('qurban_customer_id' , $request['customer_id'])->where('id' , $item['customer_address_id'])->firstOrFail();

                Livestock::where('farm_id' , $farm_id)->where('id' , $item['livestock_id'])->where('livestock_status_id' , LivestockStatusEnum::HIDUP->value)->firstOrFail();

                QurbanSaleLivestockD::create([
                    'qurban_sale_livestock_h_id' => $header->id,
                    'qurban_customer_address_id' => $item['customer_address_id'],
                    'livestock_id' => $item['livestock_id'],
                    'weight' => $item['weight'],
                    'price_per_kg' => $item['price_per_kg'],
                    'price_per_head' => $item['price_per_head'],
                    'delivery_plan_date' => $item['delivery_plan_date'],
                ]);
            }

            DB::commit();

            $data = $header;
        } catch (\Exception $e) {
            dd($e);
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error
        ];
    }

    public function updateSalesLivestock($farmId, $id, $request)
    {
        $error = false;
        $data = null;

        DB::beginTransaction();

        try {
            $header = QurbanSaleLivestockH::where('farm_id' , $farmId)->where('id' , $id)->first();

            $header->update([
                'qurban_customer_id' => $request['customer_id'],
                'customer_id' => $request['sales_order_id'] ?? null,
                'transaction_date' => $request['transaction_date']
            ]);

            $header->qurbanSaleLivestockD()->delete();

            foreach ($request['details'] as $item) {
                QurbanCustomerAddress::where('qurban_customer_id' , $request['customer_id'])->where('id' , $item['customer_address_id'])->firstOrFail();

                Livestock::where('farm_id' , $farmId)->where('id' , $item['livestock_id'])->where('livestock_status_id' , LivestockStatusEnum::HIDUP->value)->firstOrFail();

                QurbanSaleLivestockD::create([
                    'qurban_sale_livestock_h_id' => $header->id,
                    'qurban_customer_address_id' => $item['customer_address_id'],
                    'livestock_id' => $item['livestock_id'],
                    'weight' => $item['weight'],
                    'price_per_kg' => $item['price_per_kg'],
                    'price_per_head' => $item['price_per_head'],
                    'delivery_plan_date' => $item['delivery_plan_date'],
                ]);
            }

            DB::commit();

            $data = $header;

        } catch (\Exception $e) {
            dd($e);
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error
        ];
    }

    public function deleteSalesLivestock($farmId, $id)
    {
        $error = false;

        try {
            $header = QurbanSaleLivestockH::where('farm_id' , $farmId)->where('id' , $id)->first();

            $header->qurbanSaleLivestockD()->delete();

            $header->delete();

            // Commit transaksi
            DB::commit();


        } catch (\Exception $e) {
            dd($e);
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'error' => $error
        ];
    }

}

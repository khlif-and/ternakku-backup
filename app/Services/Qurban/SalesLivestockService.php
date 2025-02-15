<?php

namespace App\Services\Qurban;

use App\Models\Farm;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Models\QurbanSaleLivestockD;
use App\Models\QurbanSaleLivestockH;


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

    public function getSalesLivestocks($farmId)
    {
        $salesLivestock = QurbanSaleLivestockH::where('farm_id', $farmId)->get();

        return $salesLivestock;
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
                // dd($item);
                QurbanSaleLivestockD::create([
                    'qurban_sale_livestock_h_id' => $header->id,
                    'qurban_customer_address_id' => $item['customer_address_id'],
                    'livestock_id' => $item['livestock_id'],
                    'min_weight' => $item['min_weight'],
                    'max_weight' => $item['max_weight'],
                    'price_per_kg' => $item['price_per_kg'],
                    'price_per_head' => $item['price_per_head'],
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

    public function updateSalesLivestock($farmId, $salesLivestockId, $request)
    {
        $validated = $request->validated();

        $error = false;
        $data = null;

        DB::beginTransaction();

        try {
            $salesLivestock = QurbanSaleLivestockH::where('farm_id' , $farmId)->where('id' , $salesLivestockId)->first();

            $salesLivestock->update([
                'qurban_customer_id'    => $validated['customer_id'],
                'Livestock_date'            => $validated['Livestock_date'],
                'quantity'              => $validated['quantity'],
                'total_weight'          => $validated['total_weight'],
                'description'           => $validated['description'],
            ]);

            $data = $salesLivestock;

            DB::commit();

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error
        ];
    }

    public function deleteSalesLivestock($farm_id, $salesLivestockId)
    {
        $error = false;

        try {
            $salesLivestock = QurbanSaleLivestockH::where('farm_id' , $farm_id)->where('id',$salesLivestockId)->first();

            $salesLivestock->delete();

            // Commit transaksi
            DB::commit();


        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'error' => $error
        ];
    }

}

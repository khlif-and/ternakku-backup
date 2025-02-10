<?php

namespace App\Services\Qurban;

use App\Models\QurbanSaleLivestockH;
use Illuminate\Support\Facades\DB;


class SalesLivestockService
{
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

        $validated = $request->validated();

        DB::beginTransaction();

        try {

            // Simpan data ke tabel SalesLivestocks
            $salesLivestock = QurbanSaleLivestockH::create([
                'farm_id'               => $farm_id,
                'qurban_customer_id'    => $validated['customer_id'],
                'Livestock_date'            => $validated['Livestock_date'],
                'quantity'              => $validated['quantity'],
                'total_weight'          => $validated['total_weight'],
                'description'           => $validated['description'],
            ]);

            // Commit transaksi
            DB::commit();

            $data = $salesLivestock;
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

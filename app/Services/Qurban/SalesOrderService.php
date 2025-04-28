<?php

namespace App\Services\Qurban;

use App\Models\QurbanSalesOrder;
use Illuminate\Support\Facades\DB;


class SalesOrderService
{
    public function getSalesOrders($farmId)
    {
        $salesOrder = QurbanSalesOrder::where('farm_id', $farmId)->get();

        return $salesOrder;
    }

    public function getSalesOrder($farmId , $salesOrderId)
    {
        $salesOrder = QurbanSalesOrder::where('farm_id', $farmId)->where('id' , $salesOrderId)->first();

        return $salesOrder;
    }

    public function storeSalesOrder($farm_id, $request)
    {
        $data = null;
        $error = false;

        $validated = $request->validated();

        DB::beginTransaction();

        try {

            // Simpan data ke tabel SalesOrders
            $salesOrder = QurbanSalesOrder::create([
                'farm_id'               => $farm_id,
                'qurban_customer_id'    => $validated['customer_id'],
                'order_date'            => $validated['order_date'],
                'quantity'              => $validated['quantity'],
                'total_weight'          => $validated['total_weight'],
                'description'           => $validated['description'],
            ]);

            // Commit transaksi
            DB::commit();

            $data = $salesOrder;
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

    public function updateSalesOrder($farmId, $salesOrderId, $request)
    {
        $validated = $request->validated();

        $error = false;
        $data = null;

        DB::beginTransaction();

        try {
            $salesOrder = QurbanSalesOrder::where('farm_id' , $farmId)->where('id' , $salesOrderId)->first();

            $salesOrder->update([
                'qurban_customer_id'    => $validated['customer_id'],
                'order_date'            => $validated['order_date'],
                'quantity'              => $validated['quantity'],
                'total_weight'          => $validated['total_weight'],
                'description'           => $validated['description'],
            ]);

            $data = $salesOrder;

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

    public function deleteSalesOrder($farm_id, $salesOrderId)
    {
        $error = false;

        try {
            $salesOrder = QurbanSalesOrder::where('farm_id' , $farm_id)->where('id',$salesOrderId)->first();

            $salesOrder->delete();

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

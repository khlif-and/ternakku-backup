<?php

namespace App\Services\Web\Qurban\SalesOrder;

use App\Models\QurbanSalesOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesOrderCoreService
{
    public function list($farm, array $filters)
    {
        $query = QurbanSalesOrder::with(['qurbanCustomer', 'qurbanSalesOrderD.livestockType'])
            ->where('farm_id', $farm->id)
            ->filterMarketing($farm->id);

        if (!empty($filters['start_date'])) {
            $query->where('order_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('order_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['qurban_customer_id'])) {
            $query->where('qurban_customer_id', $filters['qurban_customer_id']);
        }

        return $query->latest()->paginate(15);
    }

    public function find($farm, $id)
    {
        return QurbanSalesOrder::with(['qurbanCustomer', 'qurbanSalesOrderD.livestockType'])
            ->where('farm_id', $farm->id)
            ->findOrFail($id);
    }

    public function store($farm, array $data)
    {
        return DB::transaction(function () use ($farm, $data) {
            // Create Header
            $salesOrder = QurbanSalesOrder::create([
                'farm_id'            => $farm->id,
                'qurban_customer_id' => $data['customer_id'],
                'order_date'         => $data['order_date'],
                'created_by'         => Auth::id(),
            ]);

            // Create Details
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                     if ($item['quantity'] > 0) {
                        \App\Models\QurbanSalesOrderD::create([
                            'qurban_sales_order_id' => $salesOrder->id,
                            'livestock_type_id'     => $item['livestock_type_id'],
                            'quantity'              => $item['quantity'],
                            'total_weight'          => $item['total_weight'],
                        ]);
                     }
                }
            }

            return $salesOrder;
        });
    }

    public function update($farm, $id, array $data)
    {
        $salesOrder = $this->find($farm, $id);

        return DB::transaction(function () use ($salesOrder, $data) {
            // Update Header
            $salesOrder->update([
                'qurban_customer_id' => $data['customer_id'],
                'order_date'         => $data['order_date'],
            ]);

            // Update Details: Delete old and create new
            $salesOrder->qurbanSalesOrderD()->delete();

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                     if ($item['quantity'] > 0) {
                        \App\Models\QurbanSalesOrderD::create([
                            'qurban_sales_order_id' => $salesOrder->id,
                            'livestock_type_id'     => $item['livestock_type_id'],
                            'quantity'              => $item['quantity'],
                            'total_weight'          => $item['total_weight'],
                        ]);
                     }
                }
            }

            return $salesOrder;
        });
    }

    public function delete($farm, $id)
    {
        $salesOrder = $this->find($farm, $id);

        DB::transaction(function () use ($salesOrder) {
            $salesOrder->qurbanSalesOrderD()->delete();
            $salesOrder->delete();
        });
    }
}

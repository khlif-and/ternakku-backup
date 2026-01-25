<?php

namespace App\Services\Qurban;

use App\Models\QurbanSaleLivestockH;
use App\Models\QurbanSaleLivestockD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesLivestockCoreService
{
    public function list($farm, array $filters = []): array
    {
        $query = QurbanSaleLivestockH::where('farm_id', $farm->id)
            ->filterMarketing($farm->id);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['qurban_customer_id'])) {
            $query->where('qurban_customer_id', $filters['qurban_customer_id']);
        }

        return [
            'sales' => $query->with(['qurbanCustomer', 'qurbanSaleLivestockD.livestock'])->latest()->paginate(15),
            'livestocks' => $farm->livestocks()->alive()->get(),
        ];
    }

    public function find($farm, $id): QurbanSaleLivestockH
    {
        return QurbanSaleLivestockH::where('farm_id', $farm->id)
            ->with(['qurbanSaleLivestockD.livestock', 'qurbanCustomer', 'qurbanSalesOrder'])
            ->findOrFail($id);
    }

    public function store($farm, array $data): QurbanSaleLivestockH
    {
        return DB::transaction(function () use ($farm, $data) {
            $header = QurbanSaleLivestockH::create([
                'farm_id'               => $farm->id,
                'qurban_customer_id'    => $data['customer_id'],
                'qurban_sales_order_id' => $data['sales_order_id'] ?? null,
                'transaction_date'      => $data['transaction_date'],
                'notes'                 => $data['notes'] ?? null,
                'created_by'            => Auth::id(),
            ]);

            foreach ($data['details'] as $detail) {
                QurbanSaleLivestockD::create([
                    'qurban_sale_livestock_h_id' => $header->id,
                    'qurban_customer_address_id' => $detail['customer_address_id'],
                    'livestock_id'               => $detail['livestock_id'],
                    'min_weight'                 => $detail['weight'],
                    'max_weight'                 => $detail['weight'],
                    'price_per_kg'               => $detail['price_per_kg'],
                    'price_per_head'             => $detail['price_per_head'],
                    'delivery_plan_date'         => $detail['delivery_plan_date'] ?? null,
                ]);
            }

            return $header;
        });
    }

    public function update($farm, $id, array $data): QurbanSaleLivestockH
    {
        $header = $this->find($farm, $id);

        return DB::transaction(function () use ($header, $data) {
            $header->update([
                'qurban_customer_id'    => $data['customer_id'],
                'qurban_sales_order_id' => $data['sales_order_id'] ?? null,
                'transaction_date'      => $data['transaction_date'],
                'notes'                 => $data['notes'] ?? null,
            ]);

            $header->qurbanSaleLivestockD()->delete();

            foreach ($data['details'] as $detail) {
                QurbanSaleLivestockD::create([
                    'qurban_sale_livestock_h_id' => $header->id,
                    'qurban_customer_address_id' => $detail['customer_address_id'],
                    'livestock_id'               => $detail['livestock_id'],
                    'min_weight'                 => $detail['weight'],
                    'max_weight'                 => $detail['weight'],
                    'price_per_kg'               => $detail['price_per_kg'],
                    'price_per_head'             => $detail['price_per_head'],
                    'delivery_plan_date'         => $detail['delivery_plan_date'] ?? null,
                ]);
            }

            return $header;
        });
    }

    public function delete($farm, $id): void
    {
        $header = $this->find($farm, $id);
        
        DB::transaction(function () use ($header) {
            $header->qurbanSaleLivestockD()->delete();
            $header->delete();
        });
    }
}
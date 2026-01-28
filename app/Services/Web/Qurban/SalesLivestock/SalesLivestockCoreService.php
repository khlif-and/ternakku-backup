<?php

namespace App\Services\Web\Qurban\SalesLivestock;

use App\Models\Farm;
use App\Models\Livestock;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Models\QurbanSaleLivestockD;
use App\Models\QurbanSaleLivestockH;
use App\Models\QurbanCustomerAddress;

class SalesLivestockCoreService
{
    public function getAvailableLivestock($farmId)
    {
        $farm = Farm::findOrFail($farmId);

        $livestocks = $farm->livestocks()->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)->get();

        $salesLivestockIds = QurbanSaleLivestockD::whereIn('livestock_id', $livestocks->pluck('id'))->pluck('livestock_id');

        $livestockAvailable = $livestocks->whereNotIn('id', $salesLivestockIds);

        return $livestockAvailable;
    }

    public function list(Farm $farm, array $filters = [])
    {
        $query = QurbanSaleLivestockH::where('farm_id', $farm->id)->filterMarketing($farm->id);

        if (!empty($filters['qurban_customer_id'])) {
            $query->where('qurban_customer_id', $filters['qurban_customer_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('transaction_date', '<=', $filters['end_date']);
        }

        return $query->latest()->paginate(10);
    }

    public function store(Farm $farm, array $data)
    {
        DB::beginTransaction();

        try {
            $header = QurbanSaleLivestockH::create([
                'farm_id' => $farm->id,
                'qurban_customer_id' => $data['customer_id'],
                'customer_id' => $data['sales_order_id'] ?? null,
                'transaction_date' => $data['transaction_date'],
                'created_by' => auth()->id(),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                QurbanCustomerAddress::where('qurban_customer_id', $data['customer_id'])
                    ->where('id', $item['customer_address_id'])
                    ->firstOrFail();

                Livestock::where('farm_id', $farm->id)
                    ->where('id', $item['livestock_id'])
                    ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                    ->firstOrFail();

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
            return $header;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Farm $farm, $id, array $data)
    {
        DB::beginTransaction();

        try {
            $header = QurbanSaleLivestockH::where('farm_id', $farm->id)->findOrFail($id);

            $header->update([
                'qurban_customer_id' => $data['customer_id'],
                'customer_id' => $data['sales_order_id'] ?? null,
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $header->qurbanSaleLivestockD()->delete();

            foreach ($data['items'] as $item) {
                QurbanCustomerAddress::where('qurban_customer_id', $data['customer_id'])
                    ->where('id', $item['customer_address_id'])
                    ->firstOrFail();

                Livestock::where('farm_id', $farm->id)
                    ->where('id', $item['livestock_id'])
                    ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                    ->firstOrFail();

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
            return $header;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Farm $farm, $id)
    {
        DB::beginTransaction();

        try {
            $header = QurbanSaleLivestockH::where('farm_id', $farm->id)->findOrFail($id);
            $header->qurbanSaleLivestockD()->delete();
            $header->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
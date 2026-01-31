<?php

namespace App\Services\Web\Qurban\LivestockDeliveryQurban;

use Illuminate\Support\Facades\DB;

class LivestockDeliveryNoteCoreService
{
    public function listDeliveryNotes(array $filters)
    {
        $query = \App\Models\QurbanDeliveryOrderH::with(['qurbanSaleLivestockH.qurbanCustomer.user', 'qurbanDeliveryOrderD.livestock']);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        // qurban_customer_id is on the Sale Header via relation, or we check if QurbanDeliveryOrderH has it?
        // QurbanDeliveryOrderH does NOT have qurban_customer_id directly, it has qurban_sale_livestock_h_id
        if (!empty($filters['qurban_customer_id'])) {
            $query->whereHas('qurbanSaleLivestockH', function ($q) use ($filters) {
                $q->where('qurban_customer_id', $filters['qurban_customer_id']);
            });
        }

        return $query->paginate(10);
    }

    public function store(array $data)
    {
        // 1. Find the Sale Header and the specific Sale Detail for validation
        // We know livestock_id. We need to find which QurbanSaleLivestockH owns this livestock for this customer.

        $qurbanSaleLivestockD = \App\Models\QurbanSaleLivestockD::where('livestock_id', $data['livestock_id'])
            ->whereHas('qurbanSaleLivestockH', function ($q) use ($data) {
                $q->where('qurban_customer_id', $data['qurban_customer_id']);
            })
            ->firstOrFail();

        $saleHeader = $qurbanSaleLivestockD->qurbanSaleLivestockH;

        // 2. Get Customer Address (Taking the first one for now as default, since UI doesn't allow selection yet)
        $customerAddress = \App\Models\QurbanCustomerAddress::where('qurban_customer_id', $data['qurban_customer_id'])->first();

        return DB::transaction(function () use ($data, $saleHeader, $customerAddress) {
            // Create Header
            $deliveryOrderH = \App\Models\QurbanDeliveryOrderH::create([
                'farm_id' => $data['farm_id'],
                'transaction_date' => $data['delivery_date'], // delivery_date maps to transaction_date?
                'qurban_customer_address_id' => $customerAddress ? $customerAddress->id : null, // nullable?
                'qurban_sale_livestock_h_id' => $saleHeader->id,
                'status' => $data['status'] ?? 'pending',
                // transaction_number auto-generated
            ]);

            // Create Detail
            \App\Models\QurbanDeliveryOrderD::create([
                'qurban_delivery_order_h_id' => $deliveryOrderH->id,
                'livestock_id' => $data['livestock_id'],
            ]);

            return $deliveryOrderH;
        });
    }

    public function find($id)
    {
        return \App\Models\QurbanDeliveryOrderH::with(['qurbanSaleLivestockH.qurbanCustomer.user', 'qurbanDeliveryOrderD.livestock'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $deliveryOrderH = \App\Models\QurbanDeliveryOrderH::findOrFail($id);

        // Update logic. If livestock changes, we need to find new salen etc.
        // For simplicity, let's assume basic updates.

        return DB::transaction(function () use ($deliveryOrderH, $data) {
            // If customer changed, we'd need to re-fetch address and sale header. 
            // This is complex. Let's do a basic update of date and generic fields if supported.

            // If livestock_id changed, we update Detail.
            if (isset($data['livestock_id'])) {
                // Check validity like in store
                $qurbanSaleLivestockD = \App\Models\QurbanSaleLivestockD::where('livestock_id', $data['livestock_id'])
                    ->whereHas('qurbanSaleLivestockH', function ($q) use ($data) {
                        $q->where('qurban_customer_id', $data['qurban_customer_id']);
                    })->firstOrFail();

                $saleHeader = $qurbanSaleLivestockD->qurbanSaleLivestockH;
                $customerAddress = \App\Models\QurbanCustomerAddress::where('qurban_customer_id', $data['qurban_customer_id'])->first();

                $deliveryOrderH->update([
                    'transaction_date' => $data['delivery_date'],
                    'qurban_sale_livestock_h_id' => $saleHeader->id,
                    'qurban_customer_address_id' => $customerAddress ? $customerAddress->id : $deliveryOrderH->qurban_customer_address_id,
                ]);

                // Update Detail
                // Assume one detail per header for this simplified Note? 
                // or wipe and recreate details?
                // QurbanDeliveryOrderH hasMany D.

                // Let's update the first detail or create if missing
                $detail = $deliveryOrderH->qurbanDeliveryOrderD()->first();
                if ($detail) {
                    $detail->update(['livestock_id' => $data['livestock_id']]);
                } else {
                    \App\Models\QurbanDeliveryOrderD::create([
                        'qurban_delivery_order_h_id' => $deliveryOrderH->id,
                        'livestock_id' => $data['livestock_id'],
                    ]);
                }
            } else {
                // Only date update?
                $deliveryOrderH->update([
                    'transaction_date' => $data['delivery_date'],
                ]);
            }

            return $deliveryOrderH;
        });
    }

    public function delete($id): void
    {
        $deliveryOrderH = \App\Models\QurbanDeliveryOrderH::findOrFail($id);
        // Delete details first? Relations cascade?
        // Laravel usually needs manual delete if no cascade.
        $deliveryOrderH->qurbanDeliveryOrderD()->delete();
        $deliveryOrderH->delete();
    }
}

<?php

namespace App\Services\Web\Farming\FeedMedicinePurchase;

use App\Models\FeedMedicinePurchase;
use App\Models\FeedMedicinePurchaseItem;
use Illuminate\Support\Facades\DB;

class FeedMedicinePurchaseCoreService
{
    public function listPurchases($farm, array $filters)
    {
        $query = FeedMedicinePurchase::with(['feedMedicinePurchaseItem'])
            ->withCount(['feedMedicinePurchaseItem']) 
            ->where('farm_id', $farm->id);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['purchase_type'])) {
            $query->whereHas('feedMedicinePurchaseItem', function ($q) use ($filters) {
                $q->where('purchase_type', $filters['purchase_type']);
            });
        }

        return $query->orderBy('transaction_date', 'desc')->get();
    }

    public function findPurchase($farm, $id): FeedMedicinePurchase
    {
        return FeedMedicinePurchase::with(['feedMedicinePurchaseItem'])
            ->where('farm_id', $farm->id)
            ->findOrFail($id);
    }

    public function storePurchase($farm, array $data): FeedMedicinePurchase
    {
        return DB::transaction(function () use ($farm, $data) {
            $purchase = FeedMedicinePurchase::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'supplier'         => $data['supplier'],
                'notes'            => $data['notes'] ?? null,
                'total_amount'     => 0,
            ]);

            $totalAmount = 0;
            foreach ($data['items'] ?? [] as $item) {
                $total = $item['quantity'] * $item['price_per_unit'];
                $totalAmount += $total;

                FeedMedicinePurchaseItem::create([
                    'feed_medicine_purchase_id' => $purchase->id,
                    'purchase_type'             => $item['purchase_type'],
                    'item_name'                 => $item['item_name'],
                    'quantity'                  => $item['quantity'],
                    'unit'                      => $item['unit'],
                    'price_per_unit'            => $item['price_per_unit'],
                    'total_price'               => $total,
                ]);
            }

            $purchase->update(['total_amount' => $totalAmount]);
            return $purchase;
        });
    }

    public function updatePurchase($farm, $id, array $data): FeedMedicinePurchase
    {
        $purchase = $this->findPurchase($farm, $id);

        return DB::transaction(function () use ($purchase, $data) {
            $purchase->update([
                'transaction_date' => $data['transaction_date'],
                'supplier'         => $data['supplier'],
                'notes'            => $data['notes'] ?? null,
            ]);

            $purchase->feedMedicinePurchaseItem()->delete();

            $totalAmount = 0;
            foreach ($data['items'] ?? [] as $item) {
                $total = $item['quantity'] * $item['price_per_unit'];
                $totalAmount += $total;

                FeedMedicinePurchaseItem::create([
                    'feed_medicine_purchase_id' => $purchase->id,
                    'purchase_type'             => $item['purchase_type'],
                    'item_name'                 => $item['item_name'],
                    'quantity'                  => $item['quantity'],
                    'unit'                      => $item['unit'],
                    'price_per_unit'            => $item['price_per_unit'],
                    'total_price'               => $total,
                ]);
            }

            $purchase->update(['total_amount' => $totalAmount]);
            return $purchase;
        });
    }

    public function deletePurchase($farm, $id): void
    {
        $purchase = $this->findPurchase($farm, $id);
        DB::transaction(function () use ($purchase) {
            $purchase->feedMedicinePurchaseItem()->delete();
            $purchase->delete();
        });
    }
}
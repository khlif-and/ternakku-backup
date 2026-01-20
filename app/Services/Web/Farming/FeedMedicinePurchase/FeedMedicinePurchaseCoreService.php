<?php

namespace App\Services\Web\Farming\FeedMedicinePurchase;

use Illuminate\Support\Facades\DB;
use App\Models\FeedMedicinePurchase;
use App\Models\FeedMedicinePurchaseItem;

class FeedMedicinePurchaseCoreService
{
    public function listPurchases($farm, $filters)
    {
        $query = FeedMedicinePurchase::where('farm_id', $farm->id);

        if (isset($filters['start_date']) && $filters['start_date']) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date']) && $filters['end_date']) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        $data = $query->with('feedMedicinePurchaseItem')->get();

        if (isset($filters['purchase_type']) && $filters['purchase_type']) {
            $purchaseType = $filters['purchase_type'];
            $data = $data->filter(function ($purchase) use ($purchaseType) {
                return $purchase->feedMedicinePurchaseItem()->where('purchase_type', $purchaseType)->exists();
            });
        }

        return $data;
    }

    public function storePurchase($farm, array $validated)
    {
        $feedMedicinePurchase = FeedMedicinePurchase::create([
            'farm_id' => $farm->id,
            'transaction_date' => $validated['transaction_date'],
            'supplier' =>  $validated['supplier'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $totalPrice = $item['quantity'] * $item['price_per_unit'];
            $totalAmount += $totalPrice;

            FeedMedicinePurchaseItem::create([
                'feed_medicine_purchase_id' => $feedMedicinePurchase->id,
                'purchase_type' => $item['purchase_type'],
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'price_per_unit' => $item['price_per_unit'],
                'total_price' => $totalPrice,
            ]);
        }

        $feedMedicinePurchase->update([
            'total_amount' => $totalAmount,
        ]);

        return $feedMedicinePurchase;
    }

    public function findPurchase($farm, $id)
    {
        return FeedMedicinePurchase::where('farm_id', $farm->id)->findOrFail($id);
    }

    public function updatePurchase($farm, $id, array $validated)
    {
        $feedMedicinePurchase = $this->findPurchase($farm, $id);

        $feedMedicinePurchase->update([
            'transaction_date' => $validated['transaction_date'],
            'supplier' =>  $validated['supplier'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $totalAmount = 0;
        $feedMedicinePurchase->feedMedicinePurchaseItem()->delete();

        foreach ($validated['items'] as $item) {
            $totalPrice = $item['quantity'] * $item['price_per_unit'];
            $totalAmount += $totalPrice;

            FeedMedicinePurchaseItem::create([
                'feed_medicine_purchase_id' => $feedMedicinePurchase->id,
                'purchase_type' => $item['purchase_type'],
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'price_per_unit' => $item['price_per_unit'],
                'total_price' => $totalPrice,
            ]);
        }

        $feedMedicinePurchase->update([
            'total_amount' => $totalAmount,
        ]);

        return $feedMedicinePurchase;
    }

    public function deletePurchase($farm, $id)
    {
        $feedMedicinePurchase = $this->findPurchase($farm, $id);
        $feedMedicinePurchase->feedMedicinePurchaseItem()->delete();
        $feedMedicinePurchase->delete();
    }
}

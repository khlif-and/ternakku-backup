<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Farming\FeedMedicinePurchaseDetailResource;

class FeedMedicinePurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        if ($request->filled('purchase_type')) {
            $purchaseItem = $this->feedMedicinePurchaseItem()->where('purchase_type' ,  $request->input('purchase_type'))->get();
        }else{
            $purchaseItem = $this->feedMedicinePurchaseItem;
        }

        return [
            'id' => $this->id,
            'farm_id' => $this->farm_id,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'supplier' => $this->supplier,
            'total_amount' => (float) $purchaseItem->sum('total_price'),
            'notes' => $this->notes,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'items' => FeedMedicinePurchaseItemResource::collection($purchaseItem),
        ];
    }
}

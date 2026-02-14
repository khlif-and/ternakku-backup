<?php

namespace App\Services\Web\Report\FeedMedicinePurchase\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Farming\FeedMedicinePurchaseItemResource;

class FeedMedicinePurchaseReportResource extends JsonResource
{
    public function toArray($request)
    {
        // Items are already filtered in Service eager loading if purchase_type exists in filters.
        // However, the API Resource logic manually checks request inputs again. 
        // We can respect the loaded relation 'feedMedicinePurchaseItem' which might already be filtered.

        $items = $this->relationLoaded('feedMedicinePurchaseItem')
            ? $this->feedMedicinePurchaseItem
            : $this->feedMedicinePurchaseItem()->get();

        return [
            'id' => $this->id,
            'farm_id' => $this->farm_id,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'supplier' => $this->supplier,
            // Re-calculate total if items are filtered
            'total_amount' => (float) $items->sum('total_price'),
            'notes' => $this->notes,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'items' => FeedMedicinePurchaseItemResource::collection($items),
        ];
    }
}

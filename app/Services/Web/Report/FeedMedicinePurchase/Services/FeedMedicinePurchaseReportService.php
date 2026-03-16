<?php

namespace App\Services\Web\Report\FeedMedicinePurchase\Services;

use App\Models\FeedMedicinePurchase;
use App\Services\Web\Report\FeedMedicinePurchase\Resources\FeedMedicinePurchaseReportResource;
use Illuminate\Database\Eloquent\Builder;

class FeedMedicinePurchaseReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = FeedMedicinePurchase::query()
            ->where('farm_id', $farmId);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['supplier'])) {
            $query->where('supplier', 'like', '%' . $filters['supplier'] . '%');
        }

        // Filter based on Items if purchase_type is selected
        if (!empty($filters['purchase_type'])) {
            $query->whereHas('feedMedicinePurchaseItem', function ($q) use ($filters) {
                $q->where('purchase_type', $filters['purchase_type']);
            });

            // Eager load only relevant items? 
            // Eloquent 'with' constraint doesn't filter the relation on the model instance unless using advanced techniques or mapped in Resource.
            // We will handle strictly filtering the items in the Resource as per the API pattern.
            $query->with([
                'feedMedicinePurchaseItem' => function ($q) use ($filters) {
                    $q->where('purchase_type', $filters['purchase_type']);
                }
            ]);
        } else {
            $query->with('feedMedicinePurchaseItem');
        }

        return $query;
    }

    public function generateReport($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        $data = $query->get();

        return FeedMedicinePurchaseReportResource::collection($data);
    }
}

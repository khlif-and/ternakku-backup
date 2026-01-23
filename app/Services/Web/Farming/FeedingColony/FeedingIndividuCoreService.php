<?php

namespace App\Services\Web\Farming\FeedingColony;

use App\Models\FeedingH;
use App\Models\FeedingIndividuD;
use App\Models\FeedingIndividuItem;
use App\Models\LivestockExpense;
use App\Enums\LivestockExpenseTypeEnum;
use Illuminate\Support\Facades\DB;

class FeedingIndividuCoreService
{
    public function listFeedingIndividu($farm, array $filters)
    {
        $query = FeedingIndividuD::with(['feedingH', 'livestock'])
            ->withCount('feedingIndividuItems')
            ->whereHas('feedingH', function ($q) use ($farm, $filters) {
                $q->where('farm_id', $farm->id)->where('type', 'individu');

                if (!empty($filters['start_date'])) {
                    $q->where('transaction_date', '>=', $filters['start_date']);
                }
                if (!empty($filters['end_date'])) {
                    $q->where('transaction_date', '<=', $filters['end_date']);
                }
            });

        if (!empty($filters['livestock_id'])) {
            $query->where('livestock_id', $filters['livestock_id']);
        }

        return $query->get();
    }

    public function find($farm, $id): FeedingIndividuD
    {
        return FeedingIndividuD::with(['feedingH', 'feedingIndividuItems', 'livestock'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($id);
    }

    public function store($farm, array $data): FeedingIndividuD
    {
        return DB::transaction(function () use ($farm, $data) {
            $feedingH = FeedingH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type'             => 'individu',
                'notes'            => $data['notes'] ?? null,
            ]);

            $feedingIndividuD = FeedingIndividuD::create([
                'feeding_h_id' => $feedingH->id,
                'livestock_id' => $data['livestock_id'],
                'notes'        => $data['notes'] ?? null,
                'total_cost'   => 0,
            ]);

            $totalCost = 0;
            foreach ($data['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingIndividuItem::create([
                    'feeding_individu_d_id' => $feedingIndividuD->id,
                    'type'                => $item['type'],
                    'name'                => $item['name'],
                    'qty_kg'              => $item['qty_kg'],
                    'price_per_kg'        => $item['price_per_kg'],
                    'total_price'         => $totalPrice,
                ]);
            }

            $feedingIndividuD->update(['total_cost' => $totalCost]);

            // Update/Create expense
            $expense = LivestockExpense::firstOrCreate(
                [
                    'livestock_id'              => $data['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                ],
                ['amount' => 0]
            );
            $expense->update(['amount' => $expense->amount + $totalCost]);

            return $feedingIndividuD;
        });
    }

    public function delete($farm, $id): void
    {
        $feedingIndividuD = FeedingIndividuD::with(['feedingH'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($id);

        DB::transaction(function () use ($feedingIndividuD) {
            // Revert expense
            $expense = LivestockExpense::where('livestock_id', $feedingIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if ($expense) {
                $expense->update(['amount' => $expense->amount - $feedingIndividuD->total_cost]);
            }

            FeedingIndividuItem::where('feeding_individu_d_id', $feedingIndividuD->id)->delete();

            $feedingH = $feedingIndividuD->feedingH;
            $feedingIndividuD->delete();

            if ($feedingH && !$feedingH->feedingIndividuD()->exists()) {
                $feedingH->delete();
            }
        });
    }

    public function update($farm, $id, array $data): FeedingIndividuD
    {
        $feedingIndividuD = FeedingIndividuD::with(['livestock', 'feedingH'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($id);

        return DB::transaction(function () use ($feedingIndividuD, $data) {
            $feedingH = $feedingIndividuD->feedingH;
            $feedingH->update([
                'transaction_date' => $data['transaction_date'],
                'notes'            => $data['notes'] ?? null,
            ]);

            // Revert old expense
            $expense = LivestockExpense::where('livestock_id', $feedingIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if ($expense) {
                $expense->update(['amount' => $expense->amount - $feedingIndividuD->total_cost]);
            }

            // Delete old items
            FeedingIndividuItem::where('feeding_individu_d_id', $feedingIndividuD->id)->delete();

            // Create new items and calculate cost
            $totalCost = 0;
            foreach ($data['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingIndividuItem::create([
                    'feeding_individu_d_id' => $feedingIndividuD->id,
                    'type'                => $item['type'],
                    'name'                => $item['name'],
                    'qty_kg'              => $item['qty_kg'],
                    'price_per_kg'        => $item['price_per_kg'],
                    'total_price'         => $totalPrice,
                ]);
            }

            $feedingIndividuD->update([
                'notes'      => $data['notes'] ?? null,
                'total_cost' => $totalCost,
            ]);

            // Update/Create expense with new cost
            $expense = LivestockExpense::firstOrCreate(
                [
                    'livestock_id'              => $feedingIndividuD->livestock_id,
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                ],
                ['amount' => 0]
            );
            $expense->update(['amount' => $expense->amount + $totalCost]);

            return $feedingIndividuD;
        });
    }
}

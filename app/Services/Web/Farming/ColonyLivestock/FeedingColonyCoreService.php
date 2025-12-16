<?php

namespace App\Services\Web\Farming\ColonyLivestock;

use App\Models\{
    FeedingH,
    FeedingColonyD,
    FeedingColonyItem,
    FeedingColonyLivestock,
    LivestockExpense
};
use App\Enums\LivestockExpenseTypeEnum;
use Illuminate\Support\Facades\DB;

class FeedingColonyCoreService
{
    public function listFeedings($farm, array $filters): array
    {
        $query = FeedingColonyD::whereHas('feedingH', function ($q) use ($farm, $filters) {
            $q->where('farm_id', $farm->id)->where('type', 'colony');
            if (!empty($filters['start_date'])) {
                $q->where('transaction_date', '>=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $q->where('transaction_date', '<=', $filters['end_date']);
            }
        });

        if (!empty($filters['pen_id'])) {
            $query->where('pen_id', $filters['pen_id']);
        }

        return $query->get()->all();
    }

    public function storeFeeding($farm, array $data): FeedingColonyD
    {
        $pen = $farm->pens()->find($data['pen_id']);
        if (!$pen) {
            throw new \InvalidArgumentException('Pen not found.');
        }

        $livestocks = $pen->livestocks;
        if ($livestocks->isEmpty()) {
            throw new \InvalidArgumentException('No livestock in this pen.');
        }

        return DB::transaction(function () use ($farm, $data, $livestocks) {
            $feedingH = FeedingH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'colony',
                'notes' => $data['notes'] ?? null,
            ]);

            $feedingColonyD = FeedingColonyD::create([
                'feeding_h_id' => $feedingH->id,
                'pen_id' => $data['pen_id'],
                'notes' => $data['notes'] ?? null,
                'total_livestock' => $livestocks->count(),
                'total_cost' => 0,
                'average_cost' => 0,
            ]);

            $totalCost = 0;
            foreach ($data['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingColonyItem::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'qty_kg' => $item['qty_kg'],
                    'price_per_kg' => $item['price_per_kg'],
                    'total_price' => $totalPrice,
                ]);
            }

            $averageCost = $totalCost / $livestocks->count();

            $feedingColonyD->update([
                'total_cost' => $totalCost,
                'average_cost' => $averageCost,
            ]);

            foreach ($livestocks as $livestock) {
                FeedingColonyLivestock::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'livestock_id' => $livestock->id,
                ]);

                $expense = LivestockExpense::firstOrNew([
                    'livestock_id' => $livestock->id,
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                ]);

                $expense->amount = ($expense->exists ? $expense->amount : 0) + $averageCost;
                $expense->save();
            }

            return $feedingColonyD;
        });
    }

    public function findFeeding($farm, $id): FeedingColonyD
    {
        return FeedingColonyD::whereHas(
            'feedingH',
            fn($q) =>
            $q->where('farm_id', $farm->id)->where('type', 'colony')
        )->findOrFail($id);
    }

    public function updateFeeding($farm, $id, array $data): FeedingColonyD
    {
        $feedingColonyD = $this->findFeeding($farm, $id);
        $livestocks = $feedingColonyD->livestocks;

        if ($livestocks->isEmpty()) {
            throw new \InvalidArgumentException('No livestock found.');
        }

        return DB::transaction(function () use ($feedingColonyD, $livestocks, $data) {
            $feedingColonyD->feedingH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($livestocks as $livestock) {
                $expense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                if ($expense) {
                    $expense->update(['amount' => $expense->amount - $feedingColonyD->average_cost]);
                }
            }

            FeedingColonyItem::where('feeding_colony_d_id', $feedingColonyD->id)->delete();

            $totalCost = 0;
            foreach ($data['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingColonyItem::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'qty_kg' => $item['qty_kg'],
                    'price_per_kg' => $item['price_per_kg'],
                    'total_price' => $totalPrice,
                ]);
            }

            $averageCost = $totalCost / $livestocks->count();

            $feedingColonyD->update([
                'notes' => $data['notes'] ?? null,
                'total_cost' => $totalCost,
                'average_cost' => $averageCost,
            ]);

            foreach ($livestocks as $livestock) {
                $expense = LivestockExpense::firstOrNew([
                    'livestock_id' => $livestock->id,
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                ]);

                $expense->amount = ($expense->exists ? $expense->amount : 0) + $averageCost;
                $expense->save();
            }

            return $feedingColonyD;
        });
    }

    public function deleteFeeding($farm, $id): void
    {
        $feedingColonyD = $this->findFeeding($farm, $id);

        DB::transaction(function () use ($feedingColonyD) {
            foreach ($feedingColonyD->livestocks as $livestock) {
                $expense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                if ($expense) {
                    $expense->update(['amount' => $expense->amount - $feedingColonyD->average_cost]);
                }
            }

            FeedingColonyItem::where('feeding_colony_d_id', $feedingColonyD->id)->delete();
            FeedingColonyLivestock::where('feeding_colony_d_id', $feedingColonyD->id)->delete();
            $feedingColonyD->delete();
            if (!$feedingColonyD->feedingH->feedingColonyD()->exists()) {
                $feedingColonyD->feedingH->delete();
            }
        });
    }
}

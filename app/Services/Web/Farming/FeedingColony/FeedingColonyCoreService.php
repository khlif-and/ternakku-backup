<?php

namespace App\Services\Web\Farming\FeedingColony;

use App\Models\FeedingH;
use App\Models\FeedingColonyD;
use App\Models\FeedingColonyItem;
use App\Models\FeedingColonyLivestock;
use App\Models\LivestockExpense;
use App\Enums\LivestockExpenseTypeEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedingColonyCoreService
{
    public function listFeedingColonies($farm, array $filters)
    {
        $query = FeedingColonyD::with(['feedingH', 'pen'])
            ->withCount('feedingColonyItems')
            ->whereHas('feedingH', function ($q) use ($farm, $filters) {
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

        return $query->get();
    }

    public function store($farm, array $data): FeedingColonyD
    {
        $pen = $farm->pens()->find($data['pen_id']);

        if (!$pen) {
            throw new \InvalidArgumentException('Kandang tidak ditemukan.');
        }

        $livestocks = $pen->livestocks;
        $totalLivestocks = $livestocks->count();
        
        if ($totalLivestocks < 1) {
            throw new \InvalidArgumentException('Tidak ada ternak dalam kandang ini.');
        }

        return DB::transaction(function () use ($farm, $data, $pen, $livestocks, $totalLivestocks) {
            $feedingH = FeedingH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type'             => 'colony',
                'notes'            => $data['notes'] ?? null,
            ]);

            $feedingColonyD = FeedingColonyD::create([
                'feeding_h_id'    => $feedingH->id,
                'pen_id'          => $pen->id,
                'notes'           => $data['notes'] ?? null,
                'total_livestock' => $totalLivestocks,
                'total_cost'      => 0,
                'average_cost'    => 0,
            ]);

            $totalCost = 0;
            foreach ($data['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingColonyItem::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'type'                => $item['type'],
                    'name'                => $item['name'],
                    'qty_kg'              => $item['qty_kg'],
                    'price_per_kg'        => $item['price_per_kg'],
                    'total_price'         => $totalPrice,
                ]);
            }

            $averageCost = $totalLivestocks > 0 ? $totalCost / $totalLivestocks : 0;
            $feedingColonyD->update([
                'total_cost'   => $totalCost,
                'average_cost' => $averageCost,
            ]);

            foreach ($livestocks as $livestock) {
                FeedingColonyLivestock::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'livestock_id'        => $livestock->id,
                ]);

                $expense = LivestockExpense::firstOrCreate(
                    [
                        'livestock_id'              => $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    ],
                    ['amount' => 0]
                );

                $expense->update(['amount' => $expense->amount + $averageCost]);
            }

            return $feedingColonyD;
        });
    }

    public function find($farm, $id): FeedingColonyD
    {
        return FeedingColonyD::with(['feedingH', 'pen', 'livestocks', 'feedingColonyItems'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($id);
    }

    public function update($farm, $id, array $data): FeedingColonyD
    {
        $feedingColonyD = FeedingColonyD::with(['livestocks', 'feedingH'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($id);

        return DB::transaction(function () use ($feedingColonyD, $data) {
            $feedingH = $feedingColonyD->feedingH;
            $feedingH->update([
                'transaction_date' => $data['transaction_date'],
                'notes'            => $data['notes'] ?? null,
            ]);

            foreach ($feedingColonyD->livestocks as $livestock) {
                $expense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                if ($expense) {
                    $expense->update(['amount' => $expense->amount - ($feedingColonyD->average_cost ?? 0)]);
                }
            }

            FeedingColonyItem::where('feeding_colony_d_id', $feedingColonyD->id)->delete();

            $feedingColonyD->update([
                'notes'        => $data['notes'] ?? null,
                'total_cost'   => 0,
                'average_cost' => 0,
            ]);

            $totalCost = 0;
            foreach ($data['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingColonyItem::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'type'                => $item['type'],
                    'name'                => $item['name'],
                    'qty_kg'              => $item['qty_kg'],
                    'price_per_kg'        => $item['price_per_kg'],
                    'total_price'         => $totalPrice,
                ]);
            }

            $totalLivestocks = $feedingColonyD->livestocks->count();
            $averageCost = $totalLivestocks > 0 ? $totalCost / $totalLivestocks : 0;

            $feedingColonyD->update([
                'total_cost'   => $totalCost,
                'average_cost' => $averageCost,
            ]);

            foreach ($feedingColonyD->livestocks as $livestock) {
                $expense = LivestockExpense::firstOrCreate(
                    [
                        'livestock_id'              => $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    ],
                    ['amount' => 0]
                );
                $expense->update(['amount' => $expense->amount + $averageCost]);
            }

            return $feedingColonyD;
        });
    }

    public function delete($farm, $id): void
    {
        $feedingColonyD = FeedingColonyD::with(['livestocks', 'feedingH'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($id);

        DB::transaction(function () use ($feedingColonyD) {
            foreach ($feedingColonyD->livestocks as $livestock) {
                $expense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();
                if ($expense) {
                    $expense->update(['amount' => $expense->amount - ($feedingColonyD->average_cost ?? 0)]);
                }
            }

            FeedingColonyItem::where('feeding_colony_d_id', $feedingColonyD->id)->delete();
            FeedingColonyLivestock::where('feeding_colony_d_id', $feedingColonyD->id)->delete();

            $feedingH = $feedingColonyD->feedingH;
            $feedingColonyD->delete();

            if ($feedingH && !$feedingH->feedingColonyD()->exists()) {
                $feedingH->delete();
            }
        });
    }
}

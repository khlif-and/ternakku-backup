<?php

namespace App\Services\Web\Farming\TreatmentColony;

use App\Models\{
    TreatmentH,
    TreatmentColonyD,
    TreatmentColonyMedicineItem,
    TreatmentColonyTreatmentItem,
    TreatmentColonyLivestock,
    LivestockExpense,
    Disease
};
use App\Enums\LivestockExpenseTypeEnum;
use Illuminate\Support\Facades\DB;

class TreatmentColonyCoreService
{
    public function listTreatments($farm, array $filters): array
    {
        $query = TreatmentColonyD::with(['treatmentH', 'pen'])
            ->withCount(['treatmentColonyMedicineItems', 'treatmentColonyTreatmentItems'])
            ->whereHas('treatmentH', function ($q) use ($farm, $filters) {
                $q->where('farm_id', $farm->id)->where('type', 'colony');
                if (!empty($filters['start_date'])) {
                    $q->where('transaction_date', '>=', $filters['start_date']);
                }
                if (!empty($filters['end_date'])) {
                    $q->where('transaction_date', '<=', $filters['end_date']);
                }
            });

        if (!empty($filters['disease_id'])) $query->where('disease_id', $filters['disease_id']);
        if (!empty($filters['pen_id'])) $query->where('pen_id', $filters['pen_id']);

        return $query->get()->all();
    }

    public function findTreatment($farm, $id)
    {
        return TreatmentColonyD::with([
            'treatmentH',
            'pen',
            'livestocks',
            'treatmentColonyMedicineItems',
            'treatmentColonyTreatmentItems',
        ])->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
        ->findOrFail($id);
    }

    public function storeTreatment($farm, array $data): TreatmentColonyD
    {
        $pen = $farm->pens()->find($data['pen_id']);
        if (!$pen) throw new \InvalidArgumentException('Pen not found.');

        $livestocks = $pen->livestocks;
        if ($livestocks->isEmpty()) throw new \InvalidArgumentException('There is no livestock in this pen.');

        return DB::transaction(function () use ($farm, $pen, $livestocks, $data) {
            $treatmentH = TreatmentH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'colony',
                'notes' => $data['notes'] ?? null,
            ]);

            $treatmentColonyD = TreatmentColonyD::create([
                'treatment_h_id' => $treatmentH->id,
                'pen_id' => $data['pen_id'],
                'disease_id' => $data['disease_id'],
                'notes' => $data['notes'] ?? null,
                'total_livestock' => $livestocks->count(),
                'total_cost' => 0,
                'average_cost' => 0,
            ]);

            $totalCost = 0;

            foreach ($data['medicines'] as $m) {
                $total = $m['qty_per_unit'] * $m['price_per_unit'];
                $totalCost += $total;
                TreatmentColonyMedicineItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name' => $m['name'],
                    'unit' => $m['unit'],
                    'qty_per_unit' => $m['qty_per_unit'],
                    'price_per_unit' => $m['price_per_unit'],
                    'total_price' => $total,
                ]);
            }

            foreach ($data['treatments'] as $t) {
                $totalCost += $t['cost'];
                TreatmentColonyTreatmentItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name' => $t['name'],
                    'cost' => $t['cost'],
                ]);
            }

            $averageCost = $livestocks->count() > 0 ? ($totalCost / $livestocks->count()) : 0;
            $treatmentColonyD->update(['total_cost' => $totalCost, 'average_cost' => $averageCost]);

            foreach ($livestocks as $l) {
                TreatmentColonyLivestock::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'livestock_id' => $l->id,
                ]);

                $expense = LivestockExpense::firstOrCreate(
                    [
                        'livestock_id' => $l->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                    ],
                    ['amount' => 0]
                );
                $expense->update(['amount' => $expense->amount + $averageCost]);
            }

            return $treatmentColonyD;
        });
    }

    public function updateTreatment($farm, $id, array $data): void
    {
        $treatmentColonyD = TreatmentColonyD::with(['treatmentH', 'livestocks'])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($id);

        DB::transaction(function () use ($treatmentColonyD, $data) {
            $treatmentColonyD->treatmentH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $livestocks = $treatmentColonyD->livestocks;
            $totalLivestocks = $livestocks->count();

            foreach ($livestocks as $l) {
                $exp = LivestockExpense::where('livestock_id', $l->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();
                if ($exp) $exp->update(['amount' => $exp->amount - ($treatmentColonyD->average_cost ?? 0)]);
            }

            TreatmentColonyMedicineItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyTreatmentItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();

            $totalCost = 0;

            foreach ($data['medicines'] as $m) {
                $total = $m['qty_per_unit'] * $m['price_per_unit'];
                $totalCost += $total;
                TreatmentColonyMedicineItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name' => $m['name'],
                    'unit' => $m['unit'],
                    'qty_per_unit' => $m['qty_per_unit'],
                    'price_per_unit' => $m['price_per_unit'],
                    'total_price' => $total,
                ]);
            }

            foreach ($data['treatments'] as $t) {
                $totalCost += $t['cost'];
                TreatmentColonyTreatmentItem::create([
                    'treatment_colony_d_id' => $treatmentColonyD->id,
                    'name' => $t['name'],
                    'cost' => $t['cost'],
                ]);
            }

            $averageCost = $totalLivestocks > 0 ? ($totalCost / $totalLivestocks) : 0;
            $treatmentColonyD->update([
                'disease_id' => $data['disease_id'],
                'notes' => $data['notes'] ?? null,
                'total_cost' => $totalCost,
                'average_cost' => $averageCost,
            ]);

            foreach ($livestocks as $l) {
                $exp = LivestockExpense::firstOrCreate(
                    [
                        'livestock_id' => $l->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                    ],
                    ['amount' => 0]
                );
                $exp->update(['amount' => $exp->amount + $averageCost]);
            }
        });
    }

    public function deleteTreatment($farm, $id): void
    {
        $treatmentColonyD = TreatmentColonyD::with(['treatmentH', 'livestocks'])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($id);

        DB::transaction(function () use ($treatmentColonyD) {
            foreach ($treatmentColonyD->livestocks as $l) {
                $exp = LivestockExpense::where('livestock_id', $l->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();
                if ($exp) $exp->update(['amount' => $exp->amount - ($treatmentColonyD->average_cost ?? 0)]);
            }

            TreatmentColonyMedicineItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyTreatmentItem::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();
            TreatmentColonyLivestock::where('treatment_colony_d_id', $treatmentColonyD->id)->delete();

            $treatmentH = $treatmentColonyD->treatmentH;
            $treatmentColonyD->delete();

            if ($treatmentH && !$treatmentH->treatmentColonyD()->exists()) {
                $treatmentH->delete();
            }
        });
    }
}

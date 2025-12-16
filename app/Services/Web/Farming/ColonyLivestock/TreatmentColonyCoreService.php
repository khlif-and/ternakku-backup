<?php

namespace App\Services\Web\Farming\ColonyLivestock;

use App\Models\{
    TreatmentH,
    TreatmentColonyD,
    TreatmentColonyLivestock,
    TreatmentColonyMedicineItem,
    TreatmentColonyTreatmentItem,
    LivestockExpense
};
use App\Enums\LivestockExpenseTypeEnum;
use Illuminate\Support\Facades\DB;

class TreatmentColonyCoreService
{
    public function listTreatments($farm, array $filters): array
    {
        $query = TreatmentColonyD::whereHas('treatmentH', function ($q) use ($farm, $filters) {
            $q->where('farm_id', $farm->id)->where('type', 'colony');

            if (!empty($filters['start_date'])) {
                $q->where('transaction_date', '>=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $q->where('transaction_date', '<=', $filters['end_date']);
            }
        });

        if (!empty($filters['disease_id'])) {
            $query->where('disease_id', $filters['disease_id']);
        }
        if (!empty($filters['pen_id'])) {
            $query->where('pen_id', $filters['pen_id']);
        }

        return $query->get()->all();
    }

    public function findTreatment($farm, $id): TreatmentColonyD
    {
        return TreatmentColonyD::whereHas(
            'treatmentH',
            fn($q) =>
            $q->where('farm_id', $farm->id)->where('type', 'colony')
        )->findOrFail($id);
    }

    public function storeTreatment($farm, array $data): TreatmentColonyD
    {
        $pen = $farm->pens()->find($data['pen_id']);
        if (!$pen)
            throw new \InvalidArgumentException('Pen not found.');

        $livestocks = $pen->livestocks;
        if ($livestocks->isEmpty())
            throw new \InvalidArgumentException('No livestock in this pen.');

        return DB::transaction(function () use ($farm, $data, $livestocks) {
            $treatmentH = TreatmentH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'colony',
                'notes' => $data['notes'] ?? null,
            ]);

            $colony = TreatmentColonyD::create([
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
                    'treatment_colony_d_id' => $colony->id,
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
                    'treatment_colony_d_id' => $colony->id,
                    'name' => $t['name'],
                    'cost' => $t['cost'],
                ]);
            }

            $averageCost = $totalCost / $livestocks->count();
            $colony->update([
                'total_cost' => $totalCost,
                'average_cost' => $averageCost,
            ]);

            foreach ($livestocks as $l) {
                TreatmentColonyLivestock::create([
                    'treatment_colony_d_id' => $colony->id,
                    'livestock_id' => $l->id,
                ]);

                $expense = LivestockExpense::firstOrNew([
                    'livestock_id' => $l->id,
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                ]);
                $expense->amount = ($expense->exists ? $expense->amount : 0) + $averageCost;
                $expense->save();
            }

            return $colony;
        });
    }

    public function updateTreatment($farm, $id, array $data): TreatmentColonyD
    {
        $colony = $this->findTreatment($farm, $id);
        $livestocks = $colony->livestocks;
        if ($livestocks->isEmpty())
            throw new \InvalidArgumentException('No livestock found.');

        return DB::transaction(function () use ($colony, $livestocks, $data) {
            $colony->treatmentH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($livestocks as $l) {
                $exp = LivestockExpense::where('livestock_id', $l->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();
                if ($exp)
                    $exp->update(['amount' => $exp->amount - $colony->average_cost]);
            }

            TreatmentColonyMedicineItem::where('treatment_colony_d_id', $colony->id)->delete();
            TreatmentColonyTreatmentItem::where('treatment_colony_d_id', $colony->id)->delete();

            $totalCost = 0;

            foreach ($data['medicines'] as $m) {
                $total = $m['qty_per_unit'] * $m['price_per_unit'];
                $totalCost += $total;
                TreatmentColonyMedicineItem::create([
                    'treatment_colony_d_id' => $colony->id,
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
                    'treatment_colony_d_id' => $colony->id,
                    'name' => $t['name'],
                    'cost' => $t['cost'],
                ]);
            }

            $averageCost = $totalCost / $livestocks->count();
            $colony->update([
                'disease_id' => $data['disease_id'],
                'notes' => $data['notes'] ?? null,
                'total_cost' => $totalCost,
                'average_cost' => $averageCost,
            ]);

            foreach ($livestocks as $l) {
                $exp = LivestockExpense::firstOrNew([
                    'livestock_id' => $l->id,
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                ]);
                $exp->amount = ($exp->exists ? $exp->amount : 0) + $averageCost;
                $exp->save();
            }

            return $colony;
        });
    }

    public function deleteTreatment($farm, $id): void
    {
        $colony = $this->findTreatment($farm, $id);

        DB::transaction(function () use ($colony) {
            foreach ($colony->livestocks as $l) {
                $exp = LivestockExpense::where('livestock_id', $l->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                    ->first();
                if ($exp)
                    $exp->update(['amount' => $exp->amount - $colony->average_cost]);
            }

            TreatmentColonyMedicineItem::where('treatment_colony_d_id', $colony->id)->delete();
            TreatmentColonyTreatmentItem::where('treatment_colony_d_id', $colony->id)->delete();
            TreatmentColonyLivestock::where('treatment_colony_d_id', $colony->id)->delete();
            $colony->delete();

            if (!$colony->treatmentH->treatmentColonyD()->exists()) {
                $colony->treatmentH->delete();
            }
        });
    }
}

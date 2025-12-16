<?php

namespace App\Services\Web\Farming\TreatmentIndividu;

use App\Models\{
    TreatmentH,
    TreatmentIndividuD,
    TreatmentIndividuMedicineItem,
    TreatmentIndividuTreatmentItem,
    LivestockExpense,
    Disease
};
use App\Enums\LivestockExpenseTypeEnum;
use Illuminate\Support\Facades\DB;

class TreatmentIndividuCoreService
{
    public function listTreatments($farm, array $filters): array
    {
        $query = TreatmentIndividuD::with(['treatmentH', 'livestock', 'disease'])
            ->withCount(['treatmentIndividuMedicineItems', 'treatmentIndividuTreatmentItems'])
            ->whereHas('treatmentH', function ($q) use ($farm, $filters) {
                $q->where('farm_id', $farm->id)->where('type', 'individu');
                if (!empty($filters['start_date'])) {
                    $q->where('transaction_date', '>=', $filters['start_date']);
                }
                if (!empty($filters['end_date'])) {
                    $q->where('transaction_date', '<=', $filters['end_date']);
                }
            });

        foreach ([
            'disease_id','livestock_type_id','livestock_group_id',
            'livestock_breed_id','livestock_sex_id','pen_id','livestock_id'
        ] as $filter) {
            if (!empty($filters[$filter])) {
                if (in_array($filter, ['disease_id', 'livestock_id'])) {
                    $query->where($filter, $filters[$filter]);
                } else {
                    $query->whereHas('livestock', fn($q) => $q->where($filter, $filters[$filter]));
                }
            }
        }

        return $query->get()->all();
    }

    public function findTreatment($farm, $id)
    {
        return TreatmentIndividuD::with([
            'treatmentH','livestock','treatmentIndividuMedicineItems','treatmentIndividuTreatmentItems'
        ])->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type','individu'))
        ->findOrFail($id);
    }

    public function storeTreatment($farm, array $data): TreatmentIndividuD
    {
        $livestock = $farm->livestocks()->find($data['livestock_id']);
        if (!$livestock) throw new \InvalidArgumentException('Livestock not found in this farm.');

        return DB::transaction(function () use ($farm, $data) {
            $treatmentH = TreatmentH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'individu',
                'notes' => $data['notes'] ?? null,
            ]);

            $treatmentIndividuD = TreatmentIndividuD::create([
                'treatment_h_id' => $treatmentH->id,
                'livestock_id' => $data['livestock_id'],
                'disease_id' => $data['disease_id'],
                'notes' => $data['notes'] ?? null,
                'total_cost' => 0,
            ]);

            $totalCost = 0;
            foreach ($data['medicines'] as $m) {
                $total = $m['qty_per_unit'] * $m['price_per_unit'];
                $totalCost += $total;
                TreatmentIndividuMedicineItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name' => $m['name'],
                    'unit' => $m['unit'],
                    'qty_per_unit' => $m['qty_per_unit'],
                    'price_per_unit' => $m['price_per_unit'],
                    'total_price' => $total,
                ]);
            }

            foreach ($data['treatments'] as $t) {
                $totalCost += $t['cost'];
                TreatmentIndividuTreatmentItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name' => $t['name'],
                    'cost' => $t['cost'],
                ]);
            }

            $treatmentIndividuD->update(['total_cost' => $totalCost]);

            $expense = LivestockExpense::firstOrCreate(
                [
                    'livestock_id' => $data['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                ],
                ['amount' => 0]
            );
            $expense->update(['amount' => $expense->amount + $totalCost]);

            return $treatmentIndividuD;
        });
    }

    public function updateTreatment($farm, $id, array $data): void
    {
        $treatmentIndividuD = TreatmentIndividuD::with(['treatmentH','livestock'])
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type','individu'))
            ->findOrFail($id);

        DB::transaction(function () use ($treatmentIndividuD, $data) {
            $treatmentIndividuD->treatmentH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $oldExp = LivestockExpense::where('livestock_id', $treatmentIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                ->first();
            if ($oldExp)
                $oldExp->update(['amount' => $oldExp->amount - ($treatmentIndividuD->total_cost ?? 0)]);

            TreatmentIndividuMedicineItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();
            TreatmentIndividuTreatmentItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();

            $totalCost = 0;
            foreach ($data['medicines'] as $m) {
                $total = $m['qty_per_unit'] * $m['price_per_unit'];
                $totalCost += $total;
                TreatmentIndividuMedicineItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name' => $m['name'],
                    'unit' => $m['unit'],
                    'qty_per_unit' => $m['qty_per_unit'],
                    'price_per_unit' => $m['price_per_unit'],
                    'total_price' => $total,
                ]);
            }

            foreach ($data['treatments'] as $t) {
                $totalCost += $t['cost'];
                TreatmentIndividuTreatmentItem::create([
                    'treatment_individu_d_id' => $treatmentIndividuD->id,
                    'name' => $t['name'],
                    'cost' => $t['cost'],
                ]);
            }

            $treatmentIndividuD->update([
                'livestock_id' => $data['livestock_id'],
                'disease_id' => $data['disease_id'],
                'notes' => $data['notes'] ?? null,
                'total_cost' => $totalCost,
            ]);

            $expense = LivestockExpense::firstOrCreate(
                [
                    'livestock_id' => $data['livestock_id'],
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::TREATMENT->value,
                ],
                ['amount' => 0]
            );
            $expense->update(['amount' => $expense->amount + $totalCost]);
        });
    }

    public function deleteTreatment($farm, $id): void
    {
        $treatmentIndividuD = TreatmentIndividuD::with('treatmentH','livestock')
            ->whereHas('treatmentH', fn($q) => $q->where('farm_id', $farm->id)->where('type','individu'))
            ->findOrFail($id);

        DB::transaction(function () use ($treatmentIndividuD) {
            $exp = LivestockExpense::where('livestock_id', $treatmentIndividuD->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::TREATMENT->value)
                ->first();
            if ($exp)
                $exp->update(['amount' => $exp->amount - ($treatmentIndividuD->total_cost ?? 0)]);

            TreatmentIndividuMedicineItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();
            TreatmentIndividuTreatmentItem::where('treatment_individu_d_id', $treatmentIndividuD->id)->delete();

            $treatmentH = $treatmentIndividuD->treatmentH;
            $treatmentIndividuD->delete();

            if ($treatmentH && !$treatmentH->treatmentIndividuD()->exists()) {
                $treatmentH->delete();
            }
        });
    }
}

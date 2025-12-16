<?php

namespace App\Services\Web\Farming\PregnantCheck;

use App\Models\{
    PregnantCheck,
    PregnantCheckD,
    LivestockExpense,
    ReproductionCycle
};
use App\Enums\{
    LivestockExpenseTypeEnum,
    LivestockSexEnum,
    ReproductionCycleStatusEnum
};
use Illuminate\Support\Facades\DB;

class PregnantCheckCoreService
{
    public function listChecks($farm, array $filters): array
    {
        $query = PregnantCheckD::with([
            'pregnantCheck',
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.livestockBreed',
            'reproductionCycle.livestock.pen',
        ])->whereHas('pregnantCheck', function ($q) use ($farm, $filters) {
            $q->where('farm_id', $farm->id);

            if (!empty($filters['start_date'])) {
                $q->where('transaction_date', '>=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $q->where('transaction_date', '<=', $filters['end_date']);
            }
        });

        foreach (['livestock_type_id', 'livestock_group_id', 'livestock_breed_id', 'pen_id'] as $filter) {
            if (!empty($filters[$filter])) {
                $query->whereHas('reproductionCycle.livestock', fn($qq) =>
                    $qq->where($filter, $filters[$filter])
                );
            }
        }

        $pcTable = (new PregnantCheckD)->getTable();
        return $query->orderByDesc(
            PregnantCheck::query()
                ->select('transaction_date')
                ->whereColumn('pregnant_checks.id', "{$pcTable}.pregnant_check_id")
                ->limit(1)
        )->get()->all();
    }

    public function findCheck($farm, $id)
    {
        return PregnantCheckD::with([
            'pregnantCheck',
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.livestockBreed',
            'reproductionCycle.livestock.pen',
        ])->whereHas('pregnantCheck', fn($q) => $q->where('farm_id', $farm->id))
        ->findOrFail($id);
    }

    public function storeCheck($farm, array $data): void
    {
        $livestock = $farm->livestocks()->find($data['livestock_id']);
        if (!$livestock) {
            throw new \InvalidArgumentException('Livestock not found.');
        }
        if ($livestock->livestock_sex_id !== LivestockSexEnum::BETINA->value) {
            throw new \InvalidArgumentException('Livestock is not female.');
        }

        DB::transaction(function () use ($farm, $data, $livestock) {
            $check = ReproductionCycle::where('livestock_id', $data['livestock_id'])
                ->orderByDesc('created_at')
                ->first();

            $repro = $check && $check->reproduction_cycle_status_id == ReproductionCycleStatusEnum::INSEMINATION->value
                ? $check
                : new ReproductionCycle([
                    'livestock_id' => $data['livestock_id'],
                    'insemination_type' => 'unknown',
                ]);

            $repro->reproduction_cycle_status_id =
                $data['status'] == 'PREGNANT'
                    ? ReproductionCycleStatusEnum::PREGNANT->value
                    : ReproductionCycleStatusEnum::INSEMINATION_FAILED->value;
            $repro->save();

            $transactionNumber = $this->generatePCNumber($data['transaction_date'], $farm->id);
            $pregnantCheck = PregnantCheck::withoutEvents(function () use ($farm, $data, $transactionNumber) {
                return PregnantCheck::create([
                    'farm_id' => $farm->id,
                    'transaction_number' => $transactionNumber,
                    'transaction_date' => $data['transaction_date'],
                    'notes' => $data['notes'] ?? null,
                ]);
            });

            PregnantCheckD::create([
                'reproduction_cycle_id' => $repro->id,
                'pregnant_check_id' => $pregnantCheck->id,
                'action_time' => $data['action_time'],
                'officer_name' => $data['officer_name'],
                'pregnant_number' => $livestock->pregnant_number() + 1,
                'children_number' => $livestock->children_number() + 1,
                'status' => $data['status'],
                'pregnant_age' => $data['pregnant_age'],
                'estimated_birth_date' => $data['status'] == 'PREGNANT'
                    ? getEstimatedBirthDate($livestock->livestock_type_id, $data['transaction_date'], $data['pregnant_age'])
                    : null,
                'cost' => $data['cost'],
            ]);

            $exp = LivestockExpense::firstOrNew([
                'livestock_id' => $data['livestock_id'],
                'livestock_expense_type_id' => LivestockExpenseTypeEnum::PREGNANT_CHECK->value,
            ]);
            $exp->amount = ($exp->amount ?? 0) + $data['cost'];
            $exp->save();
        });
    }

    public function updateCheck($farm, $id, array $data): void
    {
        $item = PregnantCheckD::with(['pregnantCheck', 'reproductionCycle.livestock'])
            ->whereHas('pregnantCheck', fn($q) => $q->where('farm_id', $farm->id))
            ->findOrFail($id);

        $livestock = $item->reproductionCycle->livestock;

        DB::transaction(function () use ($item, $livestock, $data) {
            $item->pregnantCheck->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $repro = $item->reproductionCycle;
            $repro->reproduction_cycle_status_id =
                $data['status'] == 'PREGNANT'
                    ? ReproductionCycleStatusEnum::PREGNANT->value
                    : ReproductionCycleStatusEnum::INSEMINATION_FAILED->value;
            $repro->save();

            $exp = LivestockExpense::firstOrNew([
                'livestock_id' => $livestock->id,
                'livestock_expense_type_id' => LivestockExpenseTypeEnum::PREGNANT_CHECK->value,
            ]);
            $exp->amount = ($exp->amount ?? 0) - ($item->cost ?? 0) + $data['cost'];
            $exp->save();

            $item->update([
                'action_time' => $data['action_time'],
                'officer_name' => $data['officer_name'],
                'status' => $data['status'],
                'pregnant_age' => $data['pregnant_age'],
                'estimated_birth_date' => $data['status'] == 'PREGNANT'
                    ? getEstimatedBirthDate($livestock->livestock_type_id, $data['transaction_date'], $data['pregnant_age'])
                    : null,
                'cost' => $data['cost'],
            ]);
        });
    }

    public function deleteCheck($farm, $id): void
    {
        $item = PregnantCheckD::with(['pregnantCheck', 'reproductionCycle.livestock'])
            ->whereHas('pregnantCheck', fn($q) => $q->where('farm_id', $farm->id))
            ->findOrFail($id);

        $livestock = $item->reproductionCycle->livestock;

        DB::transaction(function () use ($item, $livestock) {
            $exp = LivestockExpense::where('livestock_id', $livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::PREGNANT_CHECK->value)
                ->first();

            if ($exp) {
                $exp->update(['amount' => ($exp->amount ?? 0) - ($item->cost ?? 0)]);
            }

            $repro = $item->reproductionCycle;
            $preg = $item->pregnantCheck;
            $item->delete();

            if (!$preg->pregnantCheckD()->exists()) {
                $preg->delete();
            }

            if (
                !$repro->pregnantCheckD()->exists()
                && !$repro->inseminationNatural()->exists()
                && (!$repro->inseminationArtificial ? true : !$repro->inseminationArtificial()->exists())
            ) {
                $repro->delete();
            } else {
                $repro->reproduction_cycle_status_id = ReproductionCycleStatusEnum::INSEMINATION->value;
                $repro->save();
            }
        });
    }

    private function generatePCNumber(string $transactionDate, int $farmId): string
    {
        $date = \Illuminate\Support\Carbon::parse($transactionDate);
        $prefix = $date->format('ym') . '-PC-';

        $last = PregnantCheck::whereYear('transaction_date', $date->year)
            ->whereMonth('transaction_date', $date->month)
            ->where('farm_id', $farmId)
            ->orderBy('transaction_number', 'desc')
            ->first();

        $next = $last
            ? str_pad(((int) substr($last->transaction_number, -3)) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        return $prefix . $next;
    }
}

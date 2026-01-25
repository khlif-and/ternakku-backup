<?php

namespace App\Services\Web\Farming\NaturalInsemination;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\Insemination;
use App\Models\InseminationNatural;
use App\Models\LivestockExpense;
use App\Models\ReproductionCycle;
use App\Models\LivestockBreed;
use App\Enums\LivestockExpenseTypeEnum;
use App\Enums\ReproductionCycleStatusEnum;
use Illuminate\Support\Facades\DB;

class NaturalInseminationCoreService
{
    public function find($farm, $id): InseminationNatural
    {
        return InseminationNatural::with([
            'insemination',
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.livestockBreed',
            'reproductionCycle.livestock.pen',
        ])
        ->whereHas('insemination', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id)->whereRaw('LOWER(type) = ?', ['natural']);
        })
        ->findOrFail($id);
    }

    public function store(Farm $farm, array $data): InseminationNatural
    {
        $livestock = Livestock::findOrFail($data['livestock_id']);
        $this->validateBreedCompatibility($livestock, $data['sire_breed_id']);

        return DB::transaction(function () use ($farm, $livestock, $data) {
            $this->updatePreviousCycleStatus($livestock);
            $reproCycle = $this->createReproductionCycle($livestock);
            $insemination = $this->createInseminationRecord($farm, $data);

            $niRecord = $this->createNaturalInseminationRecord(
                $reproCycle,
                $insemination,
                $livestock,
                $data
            );

            $this->updateLivestockExpense($livestock, $data['cost']);

            return $niRecord;
        });
    }

    public function update($farm, $id, array $data): InseminationNatural
    {
        $niRecord = $this->find($farm, $id);
        $livestock = $niRecord->reproductionCycle->livestock;
        $this->validateBreedCompatibility($livestock, $data['sire_breed_id']);

        return DB::transaction(function () use ($niRecord, $livestock, $data) {
            $niRecord->insemination->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->adjustLivestockExpense($livestock, $niRecord->cost, $data['cost']);

            $niRecord->update([
                'action_time' => $data['action_time'],
                'sire_breed_id' => $data['sire_breed_id'],
                'sire_owner_name' => $data['sire_owner_name'],
                'cycle_date' => getInseminationCycleDate(
                    $livestock->livestock_type_id,
                    $data['transaction_date']
                ),
                'cost' => $data['cost'],
            ]);

            return $niRecord->fresh();
        });
    }

    public function delete(InseminationNatural $niRecord): bool
    {
        $livestock = $niRecord->reproductionCycle->livestock;
        $insemination = $niRecord->insemination;
        $reproCycle = $niRecord->reproductionCycle;

        return DB::transaction(function () use ($niRecord, $livestock, $insemination, $reproCycle) {
            $this->reduceLivestockExpense($livestock, $niRecord->cost);
            $niRecord->delete();

            if (!$insemination->inseminationNatural()->exists()) {
                $insemination->delete();
            }

            if ($reproCycle) {
                $reproCycle->delete();
            }

            return true;
        });
    }

    private function validateBreedCompatibility(Livestock $livestock, int $breedId): void
    {
        $breed = LivestockBreed::find($breedId);
        if (!$breed || (int)$breed->livestock_type_id !== (int)$livestock->livestock_type_id) {
            throw new \InvalidArgumentException('Ras pejantan tidak sesuai dengan jenis ternak.');
        }
    }

    private function updatePreviousCycleStatus(Livestock $livestock): void
    {
        $latestCycle = ReproductionCycle::where('livestock_id', $livestock->id)->latest()->first();
        if (!$latestCycle) return;

        $currentStatus = (int) $latestCycle->reproduction_cycle_status_id;
        if ($currentStatus === (int) ReproductionCycleStatusEnum::INSEMINATION->value) {
            $latestCycle->update(['reproduction_cycle_status_id' => ReproductionCycleStatusEnum::INSEMINATION_FAILED->value]);
        }
        if ($currentStatus === (int) ReproductionCycleStatusEnum::PREGNANT->value) {
            $latestCycle->update(['reproduction_cycle_status_id' => ReproductionCycleStatusEnum::BIRTH_FAILED->value]);
        }
    }

    private function createReproductionCycle(Livestock $livestock): ReproductionCycle
    {
        return ReproductionCycle::create([
            'livestock_id' => $livestock->id,
            'reproduction_cycle_status_id' => ReproductionCycleStatusEnum::INSEMINATION->value,
            'insemination_type' => 'natural',
        ]);
    }

    private function createInseminationRecord(Farm $farm, array $data): Insemination
    {
        return Insemination::create([
            'farm_id' => $farm->id,
            'transaction_date' => $data['transaction_date'],
            'type' => 'natural',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    private function createNaturalInseminationRecord($reproCycle, $insemination, $livestock, array $data): InseminationNatural
    {
        return InseminationNatural::create([
            'reproduction_cycle_id' => $reproCycle->id,
            'insemination_id' => $insemination->id,
            'action_time' => $data['action_time'],
            'insemination_number' => $livestock->insemination_number(),
            'pregnant_number' => $livestock->pregnant_number() + 1,
            'children_number' => $livestock->children_number() + 1,
            'sire_breed_id' => $data['sire_breed_id'],
            'sire_owner_name' => $data['sire_owner_name'],
            'cycle_date' => getInseminationCycleDate($livestock->livestock_type_id, $data['transaction_date']),
            'cost' => $data['cost'],
        ]);
    }

    private function updateLivestockExpense(Livestock $livestock, float $cost): void
    {
        $expense = LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::NI->value)
            ->first();

        if (!$expense) {
            LivestockExpense::create([
                'livestock_id' => $livestock->id,
                'livestock_expense_type_id' => LivestockExpenseTypeEnum::NI->value,
                'amount' => $cost,
            ]);
        } else {
            $expense->update(['amount' => $expense->amount + $cost]);
        }
    }

    private function adjustLivestockExpense(Livestock $livestock, float $oldCost, float $newCost): void
    {
        $expense = LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::NI->value)
            ->first();

        if ($expense) {
            $expense->update(['amount' => $expense->amount - $oldCost + $newCost]);
        }
    }

    private function reduceLivestockExpense(Livestock $livestock, float $cost): void
    {
        $expense = LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::NI->value)
            ->first();

        if ($expense) {
            $expense->update(['amount' => $expense->amount - $cost]);
        }
    }
}
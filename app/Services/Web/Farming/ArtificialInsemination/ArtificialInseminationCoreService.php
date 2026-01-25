<?php

namespace App\Services\Web\Farming\ArtificialInsemination;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\Insemination;
use App\Models\InseminationArtificial;
use App\Models\LivestockExpense;
use App\Models\ReproductionCycle;
use App\Models\LivestockBreed;
use App\Enums\LivestockExpenseTypeEnum;
use App\Enums\ReproductionCycleStatusEnum;
use Illuminate\Support\Facades\DB;

class ArtificialInseminationCoreService
{
    public function find($farm, $id): InseminationArtificial
    {
        return InseminationArtificial::with([
            'insemination',
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.livestockBreed',
            'reproductionCycle.livestock.pen',
        ])
        ->whereHas('insemination', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id)->where('type', 'artificial');
        })
        ->findOrFail($id);
    }

    public function store(Farm $farm, array $data): InseminationArtificial
    {
        $livestock = Livestock::findOrFail($data['livestock_id']);

        return DB::transaction(function () use ($farm, $livestock, $data) {
            $this->updatePreviousCycleStatus($livestock);
            $reproCycle = $this->createReproductionCycle($livestock);
            $insemination = $this->createInseminationRecord($farm, $data);

            $aiRecord = $this->createArtificialInseminationRecord(
                $reproCycle,
                $insemination,
                $livestock,
                $data
            );

            $this->updateLivestockExpense($livestock, $data['cost']);

            return $aiRecord;
        });
    }

    public function update($farm, $id, array $data): InseminationArtificial
    {
        $aiRecord = $this->find($farm, $id);
        $livestock = $aiRecord->reproductionCycle->livestock;

        return DB::transaction(function () use ($aiRecord, $livestock, $data) {
            $aiRecord->insemination->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->adjustLivestockExpense($livestock, $aiRecord->cost, $data['cost']);

            $aiRecord->update([
                'action_time' => $data['action_time'],
                'officer_name' => $data['officer_name'],
                'semen_breed_id' => $data['semen_breed_id'],
                'sire_name' => $data['sire_name'],
                'semen_producer' => $data['semen_producer'],
                'semen_batch' => $data['semen_batch'],
                'cycle_date' => getInseminationCycleDate(
                    $livestock->livestock_type_id,
                    $data['transaction_date']
                ),
                'cost' => $data['cost'],
            ]);

            return $aiRecord->fresh();
        });
    }

    public function delete(InseminationArtificial $aiRecord): bool
    {
        $livestock = $aiRecord->reproductionCycle->livestock;
        $insemination = $aiRecord->insemination;
        $reproCycle = $aiRecord->reproductionCycle;

        return DB::transaction(function () use ($aiRecord, $livestock, $insemination, $reproCycle) {
            $this->reduceLivestockExpense($livestock, $aiRecord->cost);
            $aiRecord->delete();

            if (!$insemination->inseminationArtificial()->exists()) {
                $insemination->delete();
            }

            if ($reproCycle) {
                $reproCycle->delete();
            }

            return true;
        });
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
            'insemination_type' => 'artificial',
        ]);
    }

    private function createInseminationRecord(Farm $farm, array $data): Insemination
    {
        return Insemination::create([
            'farm_id' => $farm->id,
            'transaction_date' => $data['transaction_date'],
            'type' => 'artificial',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    private function createArtificialInseminationRecord($reproCycle, $insemination, $livestock, array $data): InseminationArtificial
    {
        return InseminationArtificial::create([
            'reproduction_cycle_id' => $reproCycle->id,
            'insemination_id' => $insemination->id,
            'action_time' => $data['action_time'],
            'officer_name' => $data['officer_name'],
            'insemination_number' => $livestock->insemination_number(),
            'pregnant_number' => $livestock->pregnant_number() + 1,
            'children_number' => $livestock->children_number() + 1,
            'semen_breed_id' => $data['semen_breed_id'],
            'sire_name' => $data['sire_name'],
            'semen_producer' => $data['semen_producer'],
            'semen_batch' => $data['semen_batch'],
            'cycle_date' => getInseminationCycleDate($livestock->livestock_type_id, $data['transaction_date']),
            'cost' => $data['cost'],
        ]);
    }

    private function updateLivestockExpense(Livestock $livestock, float $cost): void
    {
        $expense = LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
            ->first();

        if (!$expense) {
            LivestockExpense::create([
                'livestock_id' => $livestock->id,
                'livestock_expense_type_id' => LivestockExpenseTypeEnum::AI->value,
                'amount' => $cost,
            ]);
        } else {
            $expense->update(['amount' => $expense->amount + $cost]);
        }
    }

    private function adjustLivestockExpense(Livestock $livestock, float $oldCost, float $newCost): void
    {
        $expense = LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
            ->first();

        if ($expense) {
            $expense->update(['amount' => $expense->amount - $oldCost + $newCost]);
        }
    }

    private function reduceLivestockExpense(Livestock $livestock, float $cost): void
    {
        $expense = LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
            ->first();

        if ($expense) {
            $expense->update(['amount' => $expense->amount - $cost]);
        }
    }
}
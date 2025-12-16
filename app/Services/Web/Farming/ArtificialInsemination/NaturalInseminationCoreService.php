<?php

namespace App\Services\Web\Farming\ArtificialInsemination;

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
use Illuminate\Support\Facades\Log;

class NaturalInseminationCoreService
{
    public function findByFarm(Farm $farm, int $id): InseminationNatural
    {
        return InseminationNatural::with(['insemination','reproductionCycle.livestock'])
            ->whereHas('insemination', fn($q) =>
                $q->where('farm_id', $farm->id)->whereRaw('LOWER(type) = ?', ['natural'])
            )
            ->findOrFail($id);
    }

    public function recordNatural(Farm $farm, array $data): InseminationNatural
    {
        $livestock = Livestock::findOrFail($data['livestock_id']);
        $this->validateBreedCompatibility($livestock, $data['sire_breed_id']);

        return DB::transaction(function () use ($farm, $livestock, $data) {
            $this->updatePreviousCycleStatus($livestock);
            $cycle = $this->createReproductionCycle($livestock);
            $insemination = $this->createInseminationRecord($farm, $data);

            $record = InseminationNatural::create([
                'reproduction_cycle_id' => $cycle->id,
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

            $this->updateLivestockExpense($livestock, $data['cost']);
            return $record;
        });
    }

    public function updateNatural(Farm $farm, int $id, array $data): InseminationNatural
    {
        $record = $this->findByFarm($farm, $id);
        $livestock = $record->reproductionCycle->livestock;
        $this->validateBreedCompatibility($livestock, $data['sire_breed_id']);

        return DB::transaction(function () use ($record, $livestock, $data) {
            $record->insemination->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->adjustLivestockExpense($livestock, $record->cost, $data['cost']);

            $record->update([
                'action_time' => $data['action_time'],
                'sire_breed_id' => $data['sire_breed_id'],
                'sire_owner_name' => $data['sire_owner_name'],
                'cycle_date' => getInseminationCycleDate($livestock->livestock_type_id, $data['transaction_date']),
                'cost' => $data['cost'],
                'notes' => $data['notes'] ?? null,
            ]);

            return $record->fresh();
        });
    }

    public function deleteNatural(Farm $farm, int $id): void
    {
        $record = $this->findByFarm($farm, $id);
        $livestock = $record->reproductionCycle->livestock;
        $insemination = $record->insemination;
        $cycle = $record->reproductionCycle;

        DB::transaction(function () use ($record, $livestock, $insemination, $cycle) {
            $this->reduceLivestockExpense($livestock, $record->cost);
            $record->delete();

            if (!$insemination->inseminationNatural()->exists()) $insemination->delete();
            if ($cycle) $cycle->delete();
        });
    }

    private function validateBreedCompatibility(Livestock $livestock, int $breedId): void
    {
        $breed = LivestockBreed::find($breedId);
        if (!$breed) throw new \InvalidArgumentException('Ras pejantan tidak ditemukan.');
        if ((int)$breed->livestock_type_id !== (int)$livestock->livestock_type_id)
            throw new \InvalidArgumentException('Ras pejantan tidak sesuai dengan jenis ternak.');
    }

    private function updatePreviousCycleStatus(Livestock $livestock): void
    {
        $latest = ReproductionCycle::where('livestock_id', $livestock->id)->latest()->first();
        if (!$latest) return;

        $status = (int)$latest->reproduction_cycle_status_id;
        if ($status === (int)ReproductionCycleStatusEnum::INSEMINATION->value)
            $latest->update(['reproduction_cycle_status_id' => ReproductionCycleStatusEnum::INSEMINATION_FAILED->value]);
        if ($status === (int)ReproductionCycleStatusEnum::PREGNANT->value)
            $latest->update(['reproduction_cycle_status_id' => ReproductionCycleStatusEnum::BIRTH_FAILED->value]);
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

    private function updateLivestockExpense(Livestock $livestock, float $cost): void
    {
        $expense = $this->getExpense($livestock);
        $expense
            ? $expense->update(['amount' => $expense->amount + $cost])
            : LivestockExpense::create([
                'livestock_id' => $livestock->id,
                'livestock_expense_type_id' => LivestockExpenseTypeEnum::NI->value,
                'amount' => $cost,
            ]);
    }

    private function adjustLivestockExpense(Livestock $livestock, float $old, float $new): void
    {
        $expense = $this->getExpense($livestock);
        if ($expense) $expense->update(['amount' => $expense->amount - $old + $new]);
    }

    private function reduceLivestockExpense(Livestock $livestock, float $cost): void
    {
        $expense = $this->getExpense($livestock);
        if ($expense) $expense->update(['amount' => $expense->amount - $cost]);
    }

    private function getExpense(Livestock $livestock)
    {
        return LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::NI->value)
            ->first();
    }
}

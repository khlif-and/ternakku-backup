<?php

namespace App\Services\Web\Farming\LivestockBirth;

use App\Models\LivestockBirth;
use App\Models\LivestockBirthD;
use App\Models\LivestockExpense;
use App\Models\ReproductionCycle;
use App\Enums\LivestockSexEnum;
use App\Enums\LivestockExpenseTypeEnum;
use App\Enums\ReproductionCycleStatusEnum;
use Illuminate\Support\Facades\DB;

class LivestockBirthCoreService
{
    public function listBirths($farm, array $filters)
    {
        $query = LivestockBirth::where('farm_id', $farm->id);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        foreach (['livestock_type_id', 'livestock_group_id', 'livestock_breed_id', 'pen_id'] as $filter) {
            if (!empty($filters[$filter])) {
                $query->whereHas('reproductionCycle.livestock', fn($q) =>
                    $q->where($filter, $filters[$filter])
                );
            }
        }

        return [
            'births' => $query->latest('transaction_date')->paginate(15),
            'femaleLivestocks' => $farm->livestocks()
                ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
                ->get(),
        ];
    }

    public function storeBirth($farm, array $data)
    {
        $livestock = $farm->livestocks()->find($data['livestock_id']);
        if (!$livestock) {
            throw new \InvalidArgumentException('Livestock not found.');
        }
        if ($livestock->livestock_sex_id !== LivestockSexEnum::BETINA->value) {
            throw new \InvalidArgumentException('Livestock is not female.');
        }

        return DB::transaction(function () use ($farm, $data) {
            $check = ReproductionCycle::where('livestock_id', $data['livestock_id'])->latest()->first();

            $reproCycle = $check &&
                in_array($check->reproduction_cycle_status_id, [
                    ReproductionCycleStatusEnum::INSEMINATION->value,
                    ReproductionCycleStatusEnum::PREGNANT->value,
                ])
                ? $check
                : new ReproductionCycle([
                    'livestock_id' => $data['livestock_id'],
                    'insemination_type' => 'unknown',
                ]);

            $reproCycle->reproduction_cycle_status_id =
                ($data['status'] !== 'ABORTUS' && isset($data['details']))
                ? ReproductionCycleStatusEnum::GAVE_BIRTH->value
                : ReproductionCycleStatusEnum::BIRTH_FAILED->value;
            $reproCycle->save();

            $birth = LivestockBirth::create([
                'reproduction_cycle_id' => $reproCycle->id,
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'officer_name' => $data['officer_name'] ?? null,
                'cost' => $data['cost'],
                'status' => $data['status'],
                'estimated_weaning' => $data['estimated_weaning'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            if ($data['status'] !== 'ABORTUS' && isset($data['details'])) {
                foreach ($data['details'] as $d) {
                    LivestockBirthD::create([
                        'livestock_birth_id' => $birth->id,
                        'livestock_sex_id' => $d['livestock_sex_id'],
                        'livestock_breed_id' => $d['livestock_breed_id'],
                        'weight' => $d['weight'],
                        'birth_order' => $d['birth_order'],
                        'status' => $d['status'],
                        'offspring_value' => $d['status'] === 'alive' ? ($d['offspring_value'] ?? null) : null,
                        'disease_id' => $d['status'] === 'dead' ? ($d['disease_id'] ?? null) : null,
                        'indication' => $d['status'] === 'dead' ? ($d['indication'] ?? null) : null,
                    ]);
                }
            }

            $this->updateExpense($data['livestock_id'], $data['cost']);

            return $birth;
        });
    }

    public function findBirth($farm, $id)
    {
        return LivestockBirth::where('farm_id', $farm->id)
            ->with(['reproductionCycle.livestock', 'livestockBirthD'])
            ->findOrFail($id);
    }

    public function updateBirth($farm, $id, array $data)
    {
        $birth = $this->findBirth($farm, $id);

        return DB::transaction(function () use ($birth, $data) {
            $cycle = $birth->reproductionCycle;
            $cycle->reproduction_cycle_status_id =
                ($data['status'] !== 'ABORTUS' && isset($data['details']))
                ? ReproductionCycleStatusEnum::GAVE_BIRTH->value
                : ReproductionCycleStatusEnum::BIRTH_FAILED->value;
            $cycle->save();

            $birth->livestockBirthD()->delete();

            if ($data['status'] !== 'ABORTUS' && isset($data['details'])) {
                foreach ($data['details'] as $d) {
                    LivestockBirthD::create([
                        'livestock_birth_id' => $birth->id,
                        'livestock_sex_id' => $d['livestock_sex_id'],
                        'livestock_breed_id' => $d['livestock_breed_id'],
                        'weight' => $d['weight'],
                        'birth_order' => $d['birth_order'],
                        'status' => $d['status'],
                        'offspring_value' => $d['status'] === 'alive' ? ($d['offspring_value'] ?? null) : null,
                        'disease_id' => $d['status'] === 'dead' ? ($d['disease_id'] ?? null) : null,
                        'indication' => $d['status'] === 'dead' ? ($d['indication'] ?? null) : null,
                    ]);
                }
            }

            $expense = LivestockExpense::where('livestock_id', $birth->reproductionCycle->livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::BIRTH->value)
                ->first();

            if (!$expense) {
                LivestockExpense::create([
                    'livestock_id' => $birth->reproductionCycle->livestock->id,
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::BIRTH->value,
                    'amount' => $data['cost'],
                ]);
            } else {
                $expense->update(['amount' => $expense->amount - $birth->cost + $data['cost']]);
            }

            $birth->update([
                'transaction_date' => $data['transaction_date'],
                'officer_name' => $data['officer_name'] ?? null,
                'cost' => $data['cost'],
                'status' => $data['status'],
                'estimated_weaning' => $data['estimated_weaning'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            return $birth;
        });
    }

    public function deleteBirth($farm, $id)
    {
        $birth = $this->findBirth($farm, $id);
        DB::transaction(function () use ($birth) {
            $livestock = $birth->reproductionCycle->livestock;

            $expense = LivestockExpense::where('livestock_id', $livestock->id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::BIRTH->value)
                ->first();
            if ($expense) {
                $expense->update(['amount' => max(0, $expense->amount - $birth->cost)]);
            }

            $cycle = $birth->reproductionCycle;
            $birth->livestockBirthD()->delete();
            $birth->delete();

            if ($cycle->inseminationArtificial || $cycle->inseminationNatural) {
                $cycle->reproduction_cycle_status_id = ReproductionCycleStatusEnum::INSEMINATION->value;
                $cycle->save();
            } else {
                $cycle->delete();
            }
        });
    }

    private function updateExpense($livestockId, $cost)
    {
        $expense = LivestockExpense::where('livestock_id', $livestockId)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::BIRTH->value)
            ->first();

        if (!$expense) {
            LivestockExpense::create([
                'livestock_id' => $livestockId,
                'livestock_expense_type_id' => LivestockExpenseTypeEnum::BIRTH->value,
                'amount' => $cost,
            ]);
        } else {
            $expense->update(['amount' => $expense->amount + $cost]);
        }
    }
}
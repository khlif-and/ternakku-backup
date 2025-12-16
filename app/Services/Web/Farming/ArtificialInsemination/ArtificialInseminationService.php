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
use Illuminate\Support\Facades\Log;

class ArtificialInseminationService
{
    public function recordInsemination(Livestock $livestock, Farm $farm, array $data): InseminationArtificial
    {
        $this->validateBreedCompatibility($livestock, $data['semen_breed_id']);

        DB::beginTransaction();
        try {
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

            DB::commit();

            return $aiRecord;
        } catch (\Throwable $e) {
            DB::rollBack();

            // 🔹 Log error detail ke file log Laravel
            Log::error('❌ AI store error', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'livestock_id' => $livestock->id ?? null,
                'farm_id' => $farm->id ?? null,
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);

            // 🔹 Lempar ulang dengan pesan asli agar Livewire bisa tangkap
            throw new \Exception("AI Store Error: " . $e->getMessage(), 0, $e);
        }
    }

    public function updateInsemination(InseminationArtificial $aiRecord, array $data): InseminationArtificial
    {
        $livestock = $aiRecord->reproductionCycle->livestock;

        $this->validateBreedCompatibility($livestock, $data['semen_breed_id']);

        DB::beginTransaction();
        try {
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
                'notes' => $data['notes'] ?? null,
            ]);

            DB::commit();

            return $aiRecord->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('❌ AI update error', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'ai_record_id' => $aiRecord->id ?? null,
                'livestock_id' => $livestock->id ?? null,
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);

            throw new \Exception("AI Update Error: " . $e->getMessage(), 0, $e);
        }
    }

    public function deleteInsemination(InseminationArtificial $aiRecord): bool
    {
        $livestock = $aiRecord->reproductionCycle->livestock;
        $insemination = $aiRecord->insemination;
        $reproCycle = $aiRecord->reproductionCycle;

        DB::beginTransaction();
        try {
            $this->reduceLivestockExpense($livestock, $aiRecord->cost);

            $aiRecord->delete();

            if (!$insemination->inseminationArtificial()->exists()) {
                $insemination->delete();
            }

            if ($reproCycle) {
                $reproCycle->delete();
            }

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('❌ AI destroy error', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'ai_record_id' => $aiRecord->id ?? null,
                'livestock_id' => $livestock->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            throw new \Exception("AI Delete Error: " . $e->getMessage(), 0, $e);
        }
    }

    private function validateBreedCompatibility(Livestock $livestock, int $breedId): void
    {
        $breed = LivestockBreed::find($breedId);

        if (!$breed) {
            throw new \InvalidArgumentException('Ras semen tidak ditemukan di database.');
        }

        if ((int) $breed->livestock_type_id !== (int) $livestock->livestock_type_id) {
            throw new \InvalidArgumentException(
                'Ras semen tidak sesuai dengan jenis ternak yang dipilih.'
            );
        }
    }

    private function updatePreviousCycleStatus(Livestock $livestock): void
    {
        $latestCycle = ReproductionCycle::where('livestock_id', $livestock->id)
            ->latest()
            ->first();

        if (!$latestCycle) return;

        $currentStatus = (int) $latestCycle->reproduction_cycle_status_id;

        if ($currentStatus === (int) ReproductionCycleStatusEnum::INSEMINATION->value) {
            $latestCycle->update([
                'reproduction_cycle_status_id' => ReproductionCycleStatusEnum::INSEMINATION_FAILED->value
            ]);
        }

        if ($currentStatus === (int) ReproductionCycleStatusEnum::PREGNANT->value) {
            $latestCycle->update([
                'reproduction_cycle_status_id' => ReproductionCycleStatusEnum::BIRTH_FAILED->value
            ]);
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

    private function createArtificialInseminationRecord(
        ReproductionCycle $reproCycle,
        Insemination $insemination,
        Livestock $livestock,
        array $data
    ): InseminationArtificial {
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
            'cycle_date' => getInseminationCycleDate(
                $livestock->livestock_type_id,
                $data['transaction_date']
            ),
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
            $expense->update([
                'amount' => $expense->amount + $cost
            ]);
        }
    }

    private function adjustLivestockExpense(Livestock $livestock, float $oldCost, float $newCost): void
    {
        $expense = LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
            ->first();

        if ($expense) {
            $expense->update([
                'amount' => $expense->amount - $oldCost + $newCost
            ]);
        }
    }

    private function reduceLivestockExpense(Livestock $livestock, float $cost): void
    {
        $expense = LivestockExpense::where('livestock_id', $livestock->id)
            ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::AI->value)
            ->first();

        if ($expense) {
            $expense->update([
                'amount' => $expense->amount - $cost
            ]);
        }
    }
}

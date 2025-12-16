<?php

namespace App\Services\Web\Farming\MilkAnalysisIndividu;

use App\Models\MilkAnalysisH;
use App\Models\MilkAnalysisIndividuD;
use App\Enums\LivestockSexEnum;
use Illuminate\Support\Facades\DB;

class MilkAnalysisIndividuCoreService
{
    public function listAnalyses($farm, array $filters): array
    {
        $query = MilkAnalysisIndividuD::whereHas('milkAnalysisH', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id)->where('type', 'individu');
        });

        if (!empty($filters['start_date'])) {
            $query->whereHas('milkAnalysisH', fn($q) =>
                $q->where('transaction_date', '>=', $filters['start_date'])
            );
        }

        if (!empty($filters['end_date'])) {
            $query->whereHas('milkAnalysisH', fn($q) =>
                $q->where('transaction_date', '<=', $filters['end_date'])
            );
        }

        if (!empty($filters['livestock_id'])) {
            $query->where('livestock_id', $filters['livestock_id']);
        }

        return [
            'analyses' => $query->with(['milkAnalysisH', 'livestock'])->latest()->paginate(15),
            'livestocks' => $farm->livestocks()
                ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
                ->get(),
        ];
    }

    public function storeAnalysis($farm, array $data): void
    {
        $livestock = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->find($data['livestock_id']);

        if (!$livestock) {
            throw new \InvalidArgumentException('Ternak tidak ditemukan atau bukan betina.');
        }

        DB::transaction(function () use ($farm, $data) {
            $milkAnalysisH = MilkAnalysisH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'individu',
                'notes' => $data['notes'] ?? null,
            ]);

            MilkAnalysisIndividuD::create([
                'milk_analysis_h_id' => $milkAnalysisH->id,
                'livestock_id' => $data['livestock_id'],
                'bj' => $data['bj'] ?? null,
                'at' => $data['at'] ?? null,
                'ab' => $data['ab'] ?? null,
                'mbrt' => $data['mbrt'] ?? null,
                'a_water' => $data['a_water'] ?? null,
                'protein' => $data['protein'] ?? null,
                'fat' => $data['fat'] ?? null,
                'snf' => $data['snf'] ?? null,
                'ts' => $data['ts'] ?? null,
                'rzn' => $data['rzn'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    public function findAnalysis($farm, $id)
    {
        return MilkAnalysisIndividuD::whereHas('milkAnalysisH', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id);
        })->with(['milkAnalysisH', 'livestock'])->findOrFail($id);
    }

    public function updateAnalysis($farm, $id, array $data): void
    {
        $analysis = $this->findAnalysis($farm, $id);

        $livestock = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->find($data['livestock_id']);

        if (!$livestock) {
            throw new \InvalidArgumentException('Ternak tidak ditemukan atau bukan betina.');
        }

        DB::transaction(function () use ($analysis, $data) {
            $analysis->milkAnalysisH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $analysis->update([
                'livestock_id' => $data['livestock_id'],
                'bj' => $data['bj'] ?? null,
                'at' => $data['at'] ?? null,
                'ab' => $data['ab'] ?? null,
                'mbrt' => $data['mbrt'] ?? null,
                'a_water' => $data['a_water'] ?? null,
                'protein' => $data['protein'] ?? null,
                'fat' => $data['fat'] ?? null,
                'snf' => $data['snf'] ?? null,
                'ts' => $data['ts'] ?? null,
                'rzn' => $data['rzn'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    public function deleteAnalysis($farm, $id): void
    {
        $analysis = $this->findAnalysis($farm, $id);

        DB::transaction(function () use ($analysis) {
            $milkAnalysisH = $analysis->milkAnalysisH;
            $analysis->delete();

            if ($milkAnalysisH->milkAnalysisIndividuD()->count() === 0) {
                $milkAnalysisH->delete();
            }
        });
    }
}

<?php

namespace App\Services\Web\Farming\MilkAnalysisGlobal;

use App\Models\MilkAnalysisGlobal;
use Illuminate\Support\Facades\DB;

class MilkAnalysisGlobalCoreService
{
    public function listAnalyses($farm, array $filters): array
    {
        $query = MilkAnalysisGlobal::where('farm_id', $farm->id);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        return [
            'analyses' => $query->latest('transaction_date')->paginate(15),
        ];
    }

    public function storeAnalysis($farm, array $data): MilkAnalysisGlobal
    {
        return DB::transaction(function () use ($farm, $data) {
            return MilkAnalysisGlobal::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
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
        return MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);
    }

    public function updateAnalysis($farm, $id, array $data): void
    {
        $analysis = $this->findAnalysis($farm, $id);

        DB::transaction(function () use ($analysis, $data) {
            $analysis->update([
                'transaction_date' => $data['transaction_date'],
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
        DB::transaction(fn() => $analysis->delete());
    }
}
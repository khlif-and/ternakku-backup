<?php

namespace App\Services\Web\Farming\MilkAnalysisGlobal;

use App\Models\MilkAnalysisGlobal;
use Illuminate\Support\Facades\DB;

class MilkAnalysisGlobalCoreService
{
    public function list($farm, array $filters = [])
    {
        $query = MilkAnalysisGlobal::where('farm_id', $farm->id);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        return $query->latest('transaction_date')->paginate(15);
    }

    public function find($farm, $id): MilkAnalysisGlobal
    {
        return MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);
    }

    public function store($farm, array $data): MilkAnalysisGlobal
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

    public function update($farm, $id, array $data): MilkAnalysisGlobal
    {
        $analysis = $this->find($farm, $id);

        return DB::transaction(function () use ($analysis, $data) {
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

            return $analysis;
        });
    }

    public function delete($farm, $id): void
    {
        $this->find($farm, $id)->delete();
    }
}
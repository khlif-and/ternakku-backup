<?php

namespace App\Services\Web\Report;

use App\Models\Farm;
use App\Models\InseminationNatural;
use Illuminate\Support\Facades\Log;

class Natural_Inseminasi_Report_Core_Service
{
    /**
     * Ambil data natural inseminasi untuk 1 farm
     */
    public function getList(Farm $farm, array $filters)
    {
        try {
            return InseminationNatural::with([
                    'insemination',
                    'reproductionCycle.livestock.livestockBreed',
                    'reproductionCycle.livestock.livestockType',
                ])
                ->whereHas('insemination', function ($q) use ($farm, $filters) {
                    $q->where('farm_id', $farm->id)
                      ->whereRaw('LOWER(type) = ?', ['natural']);

                    if (!empty($filters['start_date'])) {
                        $q->where('transaction_date', '>=', $filters['start_date']);
                    }

                    if (!empty($filters['end_date'])) {
                        $q->where('transaction_date', '<=', $filters['end_date']);
                    }
                })
                ->when(!empty($filters['livestock_id']), function ($q) use ($filters) {
                    $q->whereHas('reproductionCycle.livestock', function ($sub) use ($filters) {
                        $sub->where('id', $filters['livestock_id']);
                    });
                })
                ->orderBy('id', 'DESC')
                ->get();

        } catch (\Throwable $e) {
            Log::error('❌ Natural Inseminasi Report getList Error', [
                'farm_id' => $farm->id ?? null,
                'filters' => $filters,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }


    /**
     * Ambil detail data natural inseminasi berdasarkan farm
     */
    public function findByFarm(Farm $farm, int $id): InseminationNatural
    {
        try {
            return InseminationNatural::with([
                    'insemination',
                    'reproductionCycle.livestock.livestockBreed',
                    'reproductionCycle.livestock.livestockType',
                ])
                ->whereHas('insemination', function ($q) use ($farm) {
                    $q->where('farm_id', $farm->id)
                      ->whereRaw('LOWER(type) = ?', ['natural']);
                })
                ->findOrFail($id);

        } catch (\Throwable $e) {
            Log::error('❌ Natural Inseminasi Report findByFarm Error', [
                'farm_id' => $farm->id ?? null,
                'id'      => $id,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}

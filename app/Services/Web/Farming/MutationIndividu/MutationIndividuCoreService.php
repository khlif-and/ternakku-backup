<?php

namespace App\Services\Web\Farming\MutationIndividu;

use App\Models\MutationH;
use App\Models\MutationIndividuD;
use App\Models\PenHistory;
use Illuminate\Support\Facades\DB;

class MutationIndividuCoreService
{
    public function listMutations($farm, array $filters)
    {
        $query = MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', function ($q) use ($farm, $filters) {
                $q->where('farm_id', $farm->id)->where('type', 'individu');

                if (!empty($filters['start_date'])) {
                    $q->where('transaction_date', '>=', $filters['start_date']);
                }
                if (!empty($filters['end_date'])) {
                    $q->where('transaction_date', '<=', $filters['end_date']);
                }
            });

        $livestockFilters = [
            'livestock_type_id', 
            'livestock_group_id', 
            'livestock_breed_id', 
            'livestock_sex_id', 
            'pen_id'
        ];

        foreach ($livestockFilters as $filter) {
            if (!empty($filters[$filter])) {
                $query->whereHas('livestock', fn($q) =>
                    $q->where($filter, $filters[$filter])
                );
            }
        }

        if (!empty($filters['livestock_id'])) {
            $query->where('livestock_id', $filters['livestock_id']);
        }

        return $query->get();
    }

    public function find($farm, $id): MutationIndividuD
    {
        return MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($id);
    }

    public function store($farm, array $data): MutationIndividuD
    {
        return DB::transaction(function () use ($farm, $data) {
            $livestock = $farm->livestocks()->findOrFail($data['livestock_id']);

            $mutationH = MutationH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'individu',
                'notes' => $data['notes'] ?? null,
            ]);

            $mutationIndividuD = MutationIndividuD::create([
                'mutation_h_id' => $mutationH->id,
                'livestock_id' => $data['livestock_id'],
                'from' => $livestock->pen_id,
                'to' => $data['pen_destination'],
                'notes' => $data['notes'] ?? null,
            ]);

            PenHistory::create([
                'livestock_id' => $data['livestock_id'],
                'pen_id' => $data['pen_destination'],
            ]);

            $livestock->update(['pen_id' => $data['pen_destination']]);

            return $mutationIndividuD;
        });
    }

    public function update($farm, $id, array $data): MutationIndividuD
    {
        $mutationIndividuD = $this->find($farm, $id);
        $livestock = $mutationIndividuD->livestock;

        return DB::transaction(function () use ($mutationIndividuD, $livestock, $data) {
            $mutationH = $mutationIndividuD->mutationH;
            $mutationH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $mutationIndividuD->update([
                'notes' => $data['notes'] ?? null,
                'to' => $data['pen_destination'],
            ]);

            $penHistory = PenHistory::where('livestock_id', $livestock->id)->latest()->first();
            if ($penHistory) {
                $penHistory->update(['pen_id' => $data['pen_destination']]);
            } else {
                PenHistory::create([
                    'livestock_id' => $livestock->id,
                    'pen_id' => $data['pen_destination'],
                ]);
            }

            $livestock->update(['pen_id' => $data['pen_destination']]);

            return $mutationIndividuD;
        });
    }

    public function delete($farm, $id): void
    {
        $mutationIndividuD = $this->find($farm, $id);
        $livestock = $mutationIndividuD->livestock;

        DB::transaction(function () use ($mutationIndividuD, $livestock) {
            $livestock->update(['pen_id' => $mutationIndividuD->from]);

            $penHistory = PenHistory::where('livestock_id', $livestock->id)->latest()->first();
            if ($penHistory) {
                $penHistory->delete();
            }

            $mutationH = $mutationIndividuD->mutationH;
            $mutationIndividuD->delete();

            if ($mutationH && !$mutationH->mutationIndividuD()->exists()) {
                $mutationH->delete();
            }
        });
    }

    public function checkIsLatest($mutationIndividuD): bool
    {
        return $mutationIndividuD->to === ($mutationIndividuD->livestock->pen_id ?? null);
    }
}
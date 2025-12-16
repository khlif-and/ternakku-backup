<?php

namespace App\Services\Web\Farming\MutationIndividu;

use App\Models\{MutationH, MutationIndividuD, PenHistory};
use Illuminate\Support\Facades\DB;

class MutationIndividuCoreService
{
    public function listMutations($farm, array $filters): array
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

        foreach (['livestock_type_id', 'livestock_group_id', 'livestock_breed_id', 'livestock_sex_id', 'pen_id'] as $filter) {
            if (!empty($filters[$filter])) {
                $query->whereHas('livestock', fn($q) =>
                    $q->where($filter, $filters[$filter])
                );
            }
        }

        if (!empty($filters['livestock_id'])) {
            $query->where('livestock_id', $filters['livestock_id']);
        }

        return $query->get()->all();
    }

    public function storeMutation($farm, array $data): MutationIndividuD
    {
        $livestock = $farm->livestocks()->find($data['livestock_id']);
        if (!$livestock) {
            throw new \InvalidArgumentException('Livestock not found in this farm.');
        }

        $penDestination = $farm->pens()->find($data['pen_destination']);
        if (!$penDestination) {
            throw new \InvalidArgumentException('The destination pen not found.');
        }

        if ($penDestination->id == $livestock->pen_id) {
            throw new \InvalidArgumentException('The destination pen must be different from the current pen.');
        }

        return DB::transaction(function () use ($farm, $data, $livestock) {
            $mutationH = MutationH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'individu',
                'notes' => $data['notes'] ?? null,
            ]);

            $mutationIndividu = MutationIndividuD::create([
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

            return $mutationIndividu;
        });
    }

    public function findMutation($farm, $id)
    {
        return MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($id);
    }

    public function updateMutation($farm, $id, array $data): void
    {
        $mutation = $this->findMutation($farm, $id);
        $livestock = $mutation->livestock;

        if ($mutation->to !== ($livestock->pen_id ?? null)) {
            throw new \InvalidArgumentException('Editing is not allowed because this is an old record.');
        }

        $penDestination = $farm->pens()->find($data['pen_destination']);
        if (!$penDestination) {
            throw new \InvalidArgumentException('The destination pen not found.');
        }
        if ($penDestination->id == $mutation->from) {
            throw new \InvalidArgumentException('The destination pen must be different from the current pen.');
        }

        DB::transaction(function () use ($mutation, $livestock, $data) {
            $mutation->mutationH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $mutation->update([
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
        });
    }

    public function deleteMutation($farm, $id): void
    {
        $mutation = $this->findMutation($farm, $id);
        $livestock = $mutation->livestock;

        if ($mutation->to !== ($livestock->pen_id ?? null)) {
            throw new \InvalidArgumentException('Deleting is not allowed because this is an old record.');
        }

        DB::transaction(function () use ($mutation, $livestock) {
            $livestock->update(['pen_id' => $mutation->from]);

            $penHistory = PenHistory::where('livestock_id', $livestock->id)->latest()->first();
            if ($penHistory) {
                $penHistory->delete();
            }

            $mutation->delete();

            $mutationH = $mutation->mutationH;
            if ($mutationH && !$mutationH->mutationIndividuD()->exists()) {
                $mutationH->delete();
            }
        });
    }
}

<?php

namespace App\Services\Web\Farming\LivestockDeath;

use App\Models\Livestock;
use App\Models\LivestockDeath;
use App\Models\LivestockSaleWeightD;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;

class LivestockDeathCoreService
{
    public function listDeaths($farm, array $filters): array
    {
        $query = LivestockDeath::with(['livestock'])
            ->where('farm_id', $farm->id);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        foreach (['livestock_type_id', 'livestock_group_id', 'livestock_breed_id', 'livestock_sex_id', 'pen_id'] as $filter) {
            if (!empty($filters[$filter])) {
                $query->whereHas('livestock', function ($q) use ($filter, $filters) {
                    $q->where($filter, $filters[$filter]);
                });
            }
        }

        $deaths = $query->orderByDesc('transaction_date')->paginate(10)->appends($filters);

        return [
            'deaths' => $deaths
        ];
    }

    public function storeDeath($farm, array $data): LivestockDeath
    {
        return DB::transaction(function () use ($farm, $data) {
            $livestock = Livestock::find($data['livestock_id']);

            if (!$livestock || $livestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
                throw new \InvalidArgumentException('Ternak tidak ditemukan atau sudah mati.');
            }

            LivestockSaleWeightD::where('livestock_id', $livestock->id)->delete();

            $death = LivestockDeath::create([
                'farm_id'        => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'livestock_id'   => $data['livestock_id'],
                'disease_id'     => $data['disease_id'] ?? null,
                'indication'     => $data['indication'] ?? null,
                'notes'          => $data['notes'] ?? null,
            ]);

            $livestock->update(['livestock_status_id' => LivestockStatusEnum::MATI->value]);

            return $death;
        });
    }

    public function findDeath($farm, $id): LivestockDeath
    {
        return LivestockDeath::with('livestock')->where('farm_id', $farm->id)->findOrFail($id);
    }

    public function updateDeath($farm, $id, array $data): LivestockDeath
    {
        $death = $this->findDeath($farm, $id);

        return DB::transaction(function () use ($death, $data, $farm) {
            $oldLivestockId = $death->livestock_id;

            $death->update([
                'transaction_date' => $data['transaction_date'],
                'livestock_id'     => $data['livestock_id'],
                'disease_id'       => $data['disease_id'] ?? null,
                'indication'       => $data['indication'] ?? null,
                'notes'            => $data['notes'] ?? null,
            ]);

            if ($oldLivestockId && $oldLivestockId != $data['livestock_id']) {
                $old = Livestock::find($oldLivestockId);
                if ($old && $old->livestock_status_id == LivestockStatusEnum::MATI->value) {
                    $old->update(['livestock_status_id' => LivestockStatusEnum::HIDUP->value]);
                }
            }

            $new = Livestock::find($data['livestock_id']);
            if ($new && $new->livestock_status_id != LivestockStatusEnum::MATI->value) {
                $new->update(['livestock_status_id' => LivestockStatusEnum::MATI->value]);
            }

            return $death;
        });
    }

    public function deleteDeath($farm, $id): void
    {
        $death = $this->findDeath($farm, $id);

        DB::transaction(function () use ($death) {
            $livestock = Livestock::find($death->livestock_id);
            $death->delete();

            if ($livestock && $livestock->livestock_status_id == LivestockStatusEnum::MATI->value) {
                $livestock->update(['livestock_status_id' => LivestockStatusEnum::HIDUP->value]);
            }
        });
    }
}

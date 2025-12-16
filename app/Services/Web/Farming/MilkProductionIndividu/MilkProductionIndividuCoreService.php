<?php

namespace App\Services\Web\Farming\MilkProductionIndividu;

use App\Models\MilkProductionH;
use App\Models\MilkProductionIndividuD;
use App\Enums\LivestockSexEnum;
use Illuminate\Support\Facades\DB;

class MilkProductionIndividuCoreService
{
    public function listProductions($farm, array $filters): array
    {
        $query = MilkProductionIndividuD::whereHas('milkProductionH', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id)->where('type', 'individu');
        });

        if (!empty($filters['start_date'])) {
            $query->whereHas('milkProductionH', fn($q) =>
                $q->where('transaction_date', '>=', $filters['start_date'])
            );
        }

        if (!empty($filters['end_date'])) {
            $query->whereHas('milkProductionH', fn($q) =>
                $q->where('transaction_date', '<=', $filters['end_date'])
            );
        }

        if (!empty($filters['livestock_id'])) {
            $query->where('livestock_id', $filters['livestock_id']);
        }

        return [
            'productions' => $query->with(['milkProductionH', 'livestock'])->latest()->paginate(15),
            'livestocks' => $farm->livestocks()
                ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
                ->get(),
        ];
    }

    public function storeProduction($farm, array $data): void
    {
        $livestock = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->find($data['livestock_id']);

        if (!$livestock) {
            throw new \InvalidArgumentException('Ternak tidak ditemukan atau bukan betina.');
        }

        DB::transaction(function () use ($farm, $data) {
            $milkProductionH = MilkProductionH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'individu',
                'notes' => $data['notes'] ?? null,
            ]);

            MilkProductionIndividuD::create([
                'milk_production_h_id' => $milkProductionH->id,
                'livestock_id' => $data['livestock_id'],
                'milking_shift' => $data['milking_shift'],
                'milking_time' => $data['milking_time'],
                'milker_name' => $data['milker_name'],
                'quantity_liters' => $data['quantity_liters'],
                'milk_condition' => $data['milk_condition'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    public function findProduction($farm, $id)
    {
        return MilkProductionIndividuD::whereHas('milkProductionH', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id);
        })->with(['milkProductionH', 'livestock'])->findOrFail($id);
    }

    public function updateProduction($farm, $id, array $data): void
    {
        $production = $this->findProduction($farm, $id);

        $livestock = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->find($data['livestock_id']);

        if (!$livestock) {
            throw new \InvalidArgumentException('Ternak tidak ditemukan atau bukan betina.');
        }

        DB::transaction(function () use ($production, $data) {
            $production->milkProductionH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $production->update([
                'livestock_id' => $data['livestock_id'],
                'milking_shift' => $data['milking_shift'],
                'milking_time' => $data['milking_time'],
                'milker_name' => $data['milker_name'],
                'quantity_liters' => $data['quantity_liters'],
                'milk_condition' => $data['milk_condition'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    public function deleteProduction($farm, $id): void
    {
        $production = $this->findProduction($farm, $id);

        DB::transaction(function () use ($production) {
            $milkProductionH = $production->milkProductionH;
            $production->delete();

            if ($milkProductionH->milkProductionIndividuD()->count() === 0) {
                $milkProductionH->delete();
            }
        });
    }
}

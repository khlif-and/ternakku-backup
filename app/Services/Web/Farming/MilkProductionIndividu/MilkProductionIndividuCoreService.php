<?php

namespace App\Services\Web\Farming\MilkProductionIndividu;

use App\Models\MilkProductionH;
use App\Models\MilkProductionIndividuD;
use App\Enums\LivestockSexEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MilkProductionIndividuCoreService
{
    public function list($farm, array $filters = []): array
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

    public function find($farm, $id): MilkProductionIndividuD
    {
        return MilkProductionIndividuD::whereHas('milkProductionH', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id);
        })->with(['milkProductionH', 'livestock'])->findOrFail($id);
    }

    public function store($farm, array $data): MilkProductionIndividuD
    {
        return DB::transaction(function () use ($farm, $data) {
            $milkProductionH = MilkProductionH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'individu',
                'notes' => $data['notes'] ?? null,
            ]);

            $lastCreated = null;

            foreach ($data['items'] as $item) {
                $time = Carbon::parse($item['milking_time']);
                $shift = $time->hour < 12 ? 'morning' : 'afternoon';

                $lastCreated = MilkProductionIndividuD::create([
                    'milk_production_h_id' => $milkProductionH->id,
                    'livestock_id' => $item['livestock_id'],
                    'milking_shift' => $shift,
                    'milking_time' => $item['milking_time'],
                    'milker_name' => $data['milker_name'],
                    'quantity_liters' => $item['volume'],
                    'milk_condition' => $data['milk_condition'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]);
            }

            return $lastCreated;
        });
    }

    public function update($farm, $id, array $data): MilkProductionIndividuD
    {
        $production = $this->find($farm, $id);

        return DB::transaction(function () use ($production, $data) {
            $item = $data['items'][0] ?? null;
            $time = Carbon::parse($item['milking_time'] ?? $production->milking_time);
            $shift = $time->hour < 12 ? 'morning' : 'afternoon';

            $production->milkProductionH->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $production->update([
                'livestock_id' => $item['livestock_id'] ?? $production->livestock_id,
                'milking_shift' => $shift,
                'milking_time' => $item['milking_time'] ?? $production->milking_time,
                'milker_name' => $data['milker_name'],
                'quantity_liters' => $item['volume'] ?? $production->quantity_liters,
                'milk_condition' => $data['milk_condition'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            return $production;
        });
    }

    public function delete($farm, $id): void
    {
        $this->find($farm, $id)->delete();
    }
}
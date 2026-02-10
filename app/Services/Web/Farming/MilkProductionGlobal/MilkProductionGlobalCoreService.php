<?php

namespace App\Services\Web\Farming\MilkProductionGlobal;

use App\Models\MilkProductionGlobal;
use Illuminate\Support\Facades\DB;

class MilkProductionGlobalCoreService
{
    public function list($farm, array $filters = [])
    {
        $query = MilkProductionGlobal::where('farm_id', $farm->id);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        return $query->latest('transaction_date')->get();
    }

    public function find($farm, $id): MilkProductionGlobal
    {
        return MilkProductionGlobal::where('farm_id', $farm->id)->findOrFail($id);
    }

    public function store($farm, array $data): MilkProductionGlobal
    {
        return DB::transaction(function () use ($farm, $data) {
            return MilkProductionGlobal::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'milking_shift' => $data['milking_shift'],
                'milking_time' => $data['milking_time'],
                'milker_name' => $data['milker_name'],
                'quantity_liters' => $data['quantity_liters'],
                'milk_condition' => $data['milk_condition'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    public function update($farm, $id, array $data): MilkProductionGlobal
    {
        $record = $this->find($farm, $id);

        return DB::transaction(function () use ($record, $data) {
            $record->update([
                'transaction_date' => $data['transaction_date'],
                'milking_shift' => $data['milking_shift'],
                'milking_time' => $data['milking_time'],
                'milker_name' => $data['milker_name'],
                'quantity_liters' => $data['quantity_liters'],
                'milk_condition' => $data['milk_condition'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            return $record;
        });
    }

    public function delete($farm, $id): void
    {
        $this->find($farm, $id)->delete();
    }
}
<?php

namespace App\Services\Web\Farming\MilkProductionGlobal;

use App\Models\MilkProductionGlobal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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
            $lastCreated = null;

            foreach ($data['items'] as $item) {
                $time = Carbon::parse($item['milking_time']);
                $shift = $time->hour < 12 ? 'morning' : 'afternoon';

                $lastCreated = MilkProductionGlobal::create([
                    'farm_id' => $farm->id,
                    'transaction_date' => $data['transaction_date'],
                    'milking_shift' => $shift,
                    'milking_time' => $item['milking_time'],
                    'milker_name' => $data['milker_name'],
                    'quantity_liters' => $item['volume'],
                    'milk_condition' => $data['milk_condition'],
                    'notes' => $data['notes'] ?? null,
                ]);
            }

            return $lastCreated;
        });
    }

    public function update($farm, $id, array $data): MilkProductionGlobal
    {
        $record = $this->find($farm, $id);

        return DB::transaction(function () use ($record, $data) {
            $item = $data['items'][0] ?? null;
            $time = Carbon::parse($item['milking_time'] ?? $record->milking_time);
            $shift = $time->hour < 12 ? 'morning' : 'afternoon';

            $record->update([
                'transaction_date' => $data['transaction_date'],
                'milking_shift' => $shift,
                'milking_time' => $item['milking_time'] ?? $record->milking_time,
                'milker_name' => $data['milker_name'],
                'milk_condition' => $data['milk_condition'],
                'quantity_liters' => $item['volume'] ?? $record->quantity_liters,
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
<?php

namespace App\Services\Web\Farming\TreatmentScheduleIndividu;

use App\Models\{
    TreatmentSchedule,
    TreatmentScheduleIndividu
};
use Illuminate\Support\Facades\DB;

class TreatmentScheduleIndividuCoreService
{
    public function listSchedules($farm, array $filters): array
    {
        $query = TreatmentScheduleIndividu::with(['treatmentSchedule', 'livestock'])
            ->whereHas('treatmentSchedule', function ($q) use ($farm) {
                $q->where('farm_id', $farm->id)->where('type', 'individu');
            });

        if (!empty($filters['start_date'])) {
            $query->where('schedule_date', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('schedule_date', '<=', $filters['end_date']);
        }
        if (!empty($filters['livestock_id'])) {
            $query->where('livestock_id', $filters['livestock_id']);
        }

        foreach ([
            'livestock_type_id','livestock_group_id','livestock_breed_id',
            'livestock_sex_id','pen_id'
        ] as $filter) {
            if (!empty($filters[$filter])) {
                $query->whereHas('livestock', fn($q) => $q->where($filter, $filters[$filter]));
            }
        }

        return $query->orderByDesc('schedule_date')->get()->all();
    }

    public function findSchedule($farm, $id)
    {
        return TreatmentScheduleIndividu::with(['treatmentSchedule', 'livestock'])
            ->whereHas('treatmentSchedule', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($id);
    }

    public function storeSchedule($farm, array $data): TreatmentScheduleIndividu
    {
        $livestock = $farm->livestocks()->find($data['livestock_id']);
        if (!$livestock) throw new \InvalidArgumentException('Livestock not found in this farm.');

        return DB::transaction(function () use ($farm, $data) {
            $header = TreatmentSchedule::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'type' => 'individu',
                'notes' => $data['notes'] ?? null,
            ]);

            return TreatmentScheduleIndividu::create([
                'treatment_schedule_id' => $header->id,
                'schedule_date' => $data['schedule_date'],
                'livestock_id' => $data['livestock_id'],
                'notes' => $data['notes'] ?? null,
                'medicine_name' => $data['medicine_name'] ?? null,
                'medicine_unit' => $data['medicine_unit'] ?? null,
                'medicine_qty_per_unit' => $data['medicine_qty_per_unit'] ?? null,
                'treatment_name' => $data['treatment_name'] ?? null,
            ]);
        });
    }

    public function updateSchedule($farm, $id, array $data): void
    {
        $item = TreatmentScheduleIndividu::with('treatmentSchedule')
            ->whereHas('treatmentSchedule', fn($q) =>
                $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($id);

        DB::transaction(function () use ($item, $data) {
            $item->treatmentSchedule->update([
                'transaction_date' => $data['transaction_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $item->update([
                'livestock_id' => $data['livestock_id'],
                'schedule_date' => $data['schedule_date'],
                'notes' => $data['notes'] ?? null,
                'medicine_name' => $data['medicine_name'] ?? null,
                'medicine_unit' => $data['medicine_unit'] ?? null,
                'medicine_qty_per_unit' => $data['medicine_qty_per_unit'] ?? null,
                'treatment_name' => $data['treatment_name'] ?? null,
            ]);
        });
    }

    public function deleteSchedule($farm, $id): void
    {
        $item = TreatmentScheduleIndividu::with('treatmentSchedule')
            ->whereHas('treatmentSchedule', fn($q) =>
                $q->where('farm_id', $farm->id)->where('type', 'individu'))
            ->findOrFail($id);

        DB::transaction(function () use ($item) {
            $header = $item->treatmentSchedule;
            $item->delete();

            if ($header && !$header->treatmentScheduleIndividu()->exists()) {
                $header->delete();
            }
        });
    }
}

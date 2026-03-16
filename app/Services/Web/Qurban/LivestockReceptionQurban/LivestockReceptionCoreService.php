<?php

namespace App\Services\Web\Qurban\LivestockReceptionQurban;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockReceptionH;
use App\Models\LivestockReceptionD;
use App\Models\QurbanLivestock;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class LivestockReceptionCoreService
{
    /**
     * List receptions filtered to qurban livestock only
     */
    public function list(int $farmId, array $filters = []): LengthAwarePaginator
    {
        $query = LivestockReceptionD::with([
            'livestockReceptionH',
            'livestockType',
            'livestockBreed',
            'livestockSex',
            'pen',
            'livestock.qurbanLivestock',
        ])
            ->whereHas('livestockReceptionH', function ($q) use ($farmId, $filters) {
                $q->where('farm_id', $farmId);

                if (!empty($filters['start_date'])) {
                    $q->where('transaction_date', '>=', $filters['start_date']);
                }

                if (!empty($filters['end_date'])) {
                    $q->where('transaction_date', '<=', $filters['end_date']);
                }

                if (!empty($filters['supplier'])) {
                    $q->where('supplier', 'like', '%' . $filters['supplier'] . '%');
                }
            })
            ->whereHas('livestock.qurbanLivestock');

        if (!empty($filters['livestock_type_id'])) {
            $query->where('livestock_type_id', $filters['livestock_type_id']);
        }

        $items = $query->latest('id')->get();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $filters['per_page'] ?? 15;
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($currentItems, $items->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }

    /**
     * Find a reception detail by ID (must belong to farm and be qurban livestock)
     */
    public function find(Farm $farm, $id): LivestockReceptionD
    {
        return LivestockReceptionD::with([
            'livestockReceptionH',
            'livestockType',
            'livestockBreed',
            'livestockSex',
            'pen',
            'livestock.qurbanLivestock',
        ])
            ->whereHas('livestockReceptionH', fn($q) => $q->where('farm_id', $farm->id))
            ->whereHas('livestock.qurbanLivestock')
            ->findOrFail($id);
    }

    /**
     * Store a new reception with livestock and register as qurban
     */
    public function store(Farm $farm, array $data): LivestockReceptionD
    {
        return DB::transaction(function () use ($farm, $data) {
            // Create header
            $header = LivestockReceptionH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'supplier' => $data['supplier'] ?? '',
                'notes' => $data['notes'] ?? null,
            ]);

            // Create reception detail
            $receptionData = $this->buildReceptionData($data);
            $reception = new LivestockReceptionD($receptionData);
            $reception->livestockReceptionH()->associate($header);
            $reception->save();

            // Create livestock
            $livestock = Livestock::create([
                'farm_id' => $farm->id,
                'livestock_reception_d_id' => $reception->id,
                'livestock_status_id' => LivestockStatusEnum::HIDUP->value,
                'eartag_number' => $reception->eartag_number,
                'rfid_number' => $reception->rfid_number,
                'livestock_type_id' => $reception->livestock_type_id,
                'livestock_group_id' => $reception->livestock_group_id,
                'livestock_breed_id' => $reception->livestock_breed_id,
                'livestock_sex_id' => $reception->livestock_sex_id,
                'livestock_classification_id' => $reception->livestock_classification_id,
                'pen_id' => $reception->pen_id,
                'start_age_years' => $reception->age_years,
                'start_age_months' => $reception->age_months,
                'last_weight' => $reception->weight,
                'photo' => $reception->photo ?? null,
                'characteristics' => $reception->characteristics ?? null,
            ]);

            // Register as qurban livestock
            QurbanLivestock::create([
                'livestock_id' => $livestock->id,
                'farm_id' => $farm->id,
                'price' => $data['qurban_price'] ?? 0,
            ]);

            return $reception;
        });
    }

    /**
     * Update an existing reception
     */
    public function update(Farm $farm, $id, array $data): LivestockReceptionD
    {
        $reception = $this->find($farm, $id);

        return DB::transaction(function () use ($reception, $data) {
            // Update header
            $reception->livestockReceptionH->update([
                'transaction_date' => $data['transaction_date'],
                'supplier' => $data['supplier'] ?? '',
                'notes' => $data['notes'] ?? null,
            ]);

            // Update reception detail
            $receptionData = $this->buildReceptionData($data);
            $reception->update($receptionData);

            // Update livestock
            if ($reception->livestock) {
                $reception->livestock->update([
                    'livestock_status_id' => LivestockStatusEnum::HIDUP->value,
                    'eartag_number' => $reception->eartag_number,
                    'rfid_number' => $reception->rfid_number,
                    'livestock_type_id' => $reception->livestock_type_id,
                    'livestock_group_id' => $reception->livestock_group_id,
                    'livestock_breed_id' => $reception->livestock_breed_id,
                    'livestock_sex_id' => $reception->livestock_sex_id,
                    'livestock_classification_id' => $reception->livestock_classification_id,
                    'pen_id' => $reception->pen_id,
                    'start_age_years' => $reception->age_years,
                    'start_age_months' => $reception->age_months,
                    'last_weight' => $reception->weight,
                    'photo' => $reception->photo,
                    'characteristics' => $reception->characteristics,
                ]);

                // Update qurban price
                if ($reception->livestock->qurbanLivestock) {
                    $reception->livestock->qurbanLivestock->update([
                        'price' => $data['qurban_price'] ?? 0,
                    ]);
                }
            }

            return $reception;
        });
    }

    /**
     * Delete reception and related data
     */
    public function delete(Farm $farm, $id): void
    {
        $reception = $this->find($farm, $id);

        DB::transaction(function () use ($reception) {
            $header = $reception->livestockReceptionH;

            $reception->delete();

            // Delete header if no more details
            if ($header->livestockReceptionD()->count() === 0) {
                $header->delete();
            }
        });
    }

    /**
     * Build reception data array
     */
    private function buildReceptionData(array $data): array
    {
        return [
            'eartag_number' => $data['eartag_number'],
            'rfid_number' => $data['rfid_number'] ?? null,
            'livestock_type_id' => $data['livestock_type_id'],
            'livestock_group_id' => $data['livestock_group_id'],
            'livestock_breed_id' => $data['livestock_breed_id'],
            'livestock_sex_id' => $data['livestock_sex_id'],
            'livestock_classification_id' => $data['livestock_classification_id'],
            'pen_id' => $data['pen_id'],
            'age_years' => $data['age_years'],
            'age_months' => $data['age_months'],
            'weight' => $data['weight'],
            'price_per_kg' => $data['price_per_kg'],
            'price_per_head' => $data['price_per_head'],
            'notes' => $data['notes'] ?? null,
            'characteristics' => $data['characteristics'] ?? null,
        ];
    }
}

<?php

namespace App\Services\Web\Farming\LivestockReception;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockReceptionH;
use App\Models\LivestockReceptionD;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;

class LivestockReceptionCoreService
{
    /**
     * List receptions with filters and pagination
     */
    public function listReceptions(Farm $farm, array $filters): array
    {
        $query = LivestockReceptionD::with([
            'livestockReceptionH',
            'livestockType',
            'livestockBreed',
            'livestockSex',
            'pen',
        ])->whereHas('livestockReceptionH', fn($q) => $q->where('farm_id', $farm->id));

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('eartag_number', 'like', "%{$search}%")
                  ->orWhere('rfid_number', 'like', "%{$search}%")
                  ->orWhereHas('livestockType', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('livestockBreed', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $perPage = $filters['per_page'] ?? 10;

        return [
            'receptions' => $query->latest()->paginate($perPage)->appends($filters),
            'livestocks' => Livestock::with(['livestockType', 'livestockBreed', 'livestockClassification', 'pen'])
                ->where('farm_id', $farm->id)
                ->latest()
                ->get(),
        ];
    }

    /**
     * Get form dropdown data
     */
    public function getFormData(Farm $farm): array
    {
        $farm->load('pens');

        return [
            'livestockTypes' => DB::table('livestock_types')->pluck('name', 'id'),
            'sexes' => DB::table('livestock_sexes')->pluck('name', 'id'),
            'groups' => DB::table('livestock_groups')->pluck('name', 'id'),
            'classifications' => DB::table('livestock_classifications')->pluck('name', 'id'),
        ];
    }

    /**
     * Find reception by ID
     */
    public function findReception(Farm $farm, $id): LivestockReceptionD
    {
        return LivestockReceptionD::with('livestockReceptionH')
            ->whereHas('livestockReceptionH', fn($q) => $q->where('farm_id', $farm->id))
            ->findOrFail($id);
    }

    /**
     * Store new reception with livestock
     */
    public function storeReception(Farm $farm, array $data, $photoPath = null): LivestockReceptionD
    {
        return DB::transaction(function () use ($farm, $data, $photoPath) {
            // Create header
            $header = LivestockReceptionH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'supplier' => $data['supplier'] ?? '',
                'notes' => $data['notes'] ?? null,
            ]);

            // Create reception detail
            $receptionData = $this->buildReceptionData($data, $photoPath);
            $reception = new LivestockReceptionD($receptionData);
            $reception->livestockReceptionH()->associate($header);
            $reception->save();

            // Create livestock
            $livestock = $this->createLivestock($farm, $reception);

            // Create phenotype if data exists
            $this->createPhenotype($livestock, $data);

            return $reception;
        });
    }

    /**
     * Update existing reception
     */
    public function updateReception(Farm $farm, $id, array $data, $photoPath = null): LivestockReceptionD
    {
        $reception = $this->findReception($farm, $id);

        return DB::transaction(function () use ($reception, $data, $photoPath) {
            // Update header
            $reception->livestockReceptionH->update([
                'transaction_date' => $data['transaction_date'],
                'supplier' => $data['supplier'] ?? '',
                'notes' => $data['notes'] ?? null,
            ]);

            // Update reception detail
            $receptionData = $this->buildReceptionData($data, $photoPath);
            if ($photoPath) {
                $receptionData['photo'] = $photoPath;
            }
            $reception->update($receptionData);

            // Update livestock
            $this->updateLivestock($reception);

            // Update phenotype
            $this->updatePhenotype($reception->livestock, $data);

            return $reception;
        });
    }

    /**
     * Delete reception and related data
     */
    public function deleteReception(Farm $farm, $id): void
    {
        $reception = $this->findReception($farm, $id);

        DB::transaction(function () use ($reception) {
            $header = $reception->livestockReceptionH;

            // Delete photo if exists
            if ($reception->photo && file_exists(public_path($reception->photo))) {
                unlink(public_path($reception->photo));
            }

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
    private function buildReceptionData(array $data, $photoPath = null): array
    {
        $result = [
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

        if ($photoPath) {
            $result['photo'] = $photoPath;
        }

        return $result;
    }

    /**
     * Create livestock from reception
     */
    private function createLivestock(Farm $farm, LivestockReceptionD $reception): Livestock
    {
        return Livestock::create([
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
    }

    /**
     * Update livestock from reception
     */
    private function updateLivestock(LivestockReceptionD $reception): void
    {
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
    }

    /**
     * Create phenotype data
     */
    private function createPhenotype(Livestock $livestock, array $data): void
    {
        $phenotypeData = collect($data)->only([
            'height', 'body_length', 'hip_height', 'hip_width',
            'chest_width', 'head_length', 'head_width', 'ear_length', 'body_weight'
        ])->filter();

        if ($phenotypeData->isNotEmpty()) {
            $livestock->livestockPhenotype()->create($phenotypeData->toArray());
        }
    }

    /**
     * Update phenotype data
     */
    private function updatePhenotype(Livestock $livestock, array $data): void
    {
        $phenotypeData = collect($data)->only([
            'height', 'body_length', 'hip_height', 'hip_width',
            'chest_width', 'head_length', 'head_width', 'ear_length', 'body_weight'
        ])->filter();

        if ($phenotypeData->isNotEmpty()) {
            $livestock->livestockPhenotype()->updateOrCreate([], $phenotypeData->toArray());
        }
    }
}

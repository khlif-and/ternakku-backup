<?php

namespace App\Helpers\web;

use App\Models\Farm;
use App\Models\LivestockBreed;
use Illuminate\Support\Facades\DB;

class LivestockReceptionFormService
{
    /**
     * Get dropdown data for reception form
     */
    public function getDropdownData(Farm $farm): array
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
     * Get breeds by livestock type for dependent dropdown
     */
    public function getBreedsByType(int $typeId): array
    {
        return LivestockBreed::where('livestock_type_id', $typeId)
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Handle photo upload for reception
     */
    public function uploadPhoto($photo): ?string
    {
        if (!$photo) {
            return null;
        }

        $fileName = time() . '-' . $photo->getClientOriginalName();
        return uploadNeoObject($photo, $fileName, 'receptions/');
    }

    /**
     * Delete old photo if exists
     */
    public function deletePhoto(?string $photoPath): void
    {
        if ($photoPath) {
            deleteNeoObject($photoPath);
        }
    }
}

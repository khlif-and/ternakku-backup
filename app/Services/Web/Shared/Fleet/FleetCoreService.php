<?php

namespace App\Services\Web\Shared\Fleet;

use App\Models\Farm;
use App\Models\QurbanFleet;
use Illuminate\Support\Facades\DB;

class FleetCoreService
{
    public function get(Farm $farm, $id): QurbanFleet
    {
        $fleet = QurbanFleet::where('farm_id', $farm->id)
            ->where('id', $id)
            ->with('latestPosition')
            ->firstOrFail();

        return $fleet;
    }

    public function store(Farm $farm, array $data): QurbanFleet
    {
        return DB::transaction(function () use ($farm, $data) {
            $photo = null;

            if (isset($data['photo']) && $data['photo']) {
                $file = $data['photo'];
                $fileName = time() . '-photo-' . $file->getClientOriginalName();
                $filePath = 'fleets/photos/';
                $photo = uploadNeoObject($file, $fileName, $filePath);
            }

            return QurbanFleet::create([
                'farm_id' => $farm->id,
                'name' => $data['name'],
                'police_number' => $data['police_number'],
                'photo' => $photo,
            ]);
        });
    }

    public function update(Farm $farm, $id, array $data): QurbanFleet
    {
        return DB::transaction(function () use ($farm, $id, $data) {
            $fleet = QurbanFleet::where('farm_id', $farm->id)
                ->where('id', $id)
                ->firstOrFail();

            $photo = $fleet->photo;

            if (isset($data['photo']) && $data['photo']) {
                $file = $data['photo'];
                $fileName = time() . '-photo-' . $file->getClientOriginalName();
                $filePath = 'fleets/photos/';

                if ($fleet->photo) {
                    deleteNeoObject($fleet->photo);
                }

                $photo = uploadNeoObject($file, $fileName, $filePath);
            }

            $fleet->update([
                'name' => $data['name'],
                'police_number' => $data['police_number'],
                'photo' => $photo,
            ]);

            return $fleet;
        });
    }

    public function destroy(Farm $farm, $id): void
    {
        DB::transaction(function () use ($farm, $id) {
            $fleet = QurbanFleet::where('farm_id', $farm->id)
                ->where('id', $id)
                ->firstOrFail();

            if ($fleet->photo) {
                deleteNeoObject($fleet->photo);
            }

            $fleet->delete();
        });
    }
}

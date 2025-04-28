<?php

namespace App\Services\Qurban;

use App\Models\QurbanFleet;
use Illuminate\Support\Facades\DB;


class FleetService
{
    public function getFleets($farmId)
    {
        $fleet = QurbanFleet::where('farm_id', $farmId)->get();

        return $fleet;
    }

    public function getFleet($farmId , $fleetId)
    {
        $fleet = QurbanFleet::where('farm_id', $farmId)->where('id' , $fleetId)->first();

        return $fleet;
    }

    public function storeFleet($farmId, $request)
    {
        $data = null;
        $error = false;

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $photo = null;

            // Handle logo upload if present
            if (isset($validated['photo']) && $request->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-photo-' . $file->getClientOriginalName();
                $filePath = 'fleets/photos/';
                $photo = uploadNeoObject($file, $fileName, $filePath);
            }

            // Simpan data ke tabel Fleets
            $fleet = QurbanFleet::create([
                'name'              => $validated['name'],
                'police_number'     => $validated['police_number'],
                'farm_id'           => $farmId,
                'photo'            => $photo,
            ]);

            // Commit transaksi
            DB::commit();

            $data = $fleet;
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error
        ];
    }

    public function updateFleet($farmId, $fleetId, $request)
    {
        $validated = $request->validated();

        $error = false;
        $data = null;

        DB::beginTransaction();

        try {
            $fleet = QurbanFleet::where('farm_id' , $farmId)->where('id' , $fleetId)->first();

            $photo = null;
            // Handle logo upload if present
            if (isset($validated['photo']) && $request->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-photo-' . $file->getClientOriginalName();
                $filePath = 'fleets/photos/';
                $photo = uploadNeoObject($file, $fileName, $filePath);

                if ($fleet->photo) {
                    deleteNeoObject($fleet->photo);
                }
            }

            $fleet->update([
                'name'              => $validated['name'],
                'police_number'     => $validated['police_number'],
                'photo'             => $photo,
            ]);

            $data = $fleet;

            DB::commit();

        } catch (\Exception $e) {
            dd($e);
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error
        ];
    }

    public function deleteFleet($farm_id, $fleetId)
    {
        $error = false;

        try {
            $fleet = QurbanFleet::where('farm_id' , $farm_id)->where('id',$fleetId)->first();

            if ($fleet->photo) {
                deleteNeoObject($fleet->photo);
            }

            $fleet->delete();

            // Commit transaksi
            DB::commit();


        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'error' => $error
        ];
    }

}

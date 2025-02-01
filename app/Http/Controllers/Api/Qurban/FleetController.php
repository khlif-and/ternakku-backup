<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\QurbanFleet;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Qurban\FleetResource;
use App\Http\Requests\Qurban\FleetStoreRequest;
use App\Http\Requests\Qurban\FleetUpdateRequest;

class FleetController extends Controller
{
    public function store(FleetStoreRequest $request, $farm_id)
    {
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
                'police_number'              => $validated['police_number'],
                'farm_id'          => $farm_id,

                'photo'            => $photo,
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new FleetResource($fleet), 'Fleet created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create Fleet: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $fleet = QurbanFleet::findOrFail($id);

        return ResponseHelper::success(new FleetResource($fleet), 'Fleet found', 200);
    }

    public function index()
    {
        $fleets = QurbanFleet::all();

        return ResponseHelper::success(FleetResource::collection($fleets), 'Fleets found', 200);
    }

    public function update(FleetUpdateRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $fleet = QurbanFleet::findOrFail($id);

            $photo = $fleet->photo;

            // Handle logo upload if present
            if (isset($validated['photo']) && $request->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-photo-' . $file->getClientOriginalName();
                $filePath = 'fleets/photos/';
                $photo = uploadNeoObject($file, $fileName, $filePath);
            }

            // Simpan data ke tabel Fleets
            $fleet->update([
                'name'              => $validated['name'],
                'police_number'              => $validated['police_number'],
                'photo'            => $photo,
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new FleetResource($fleet), 'Fleet updated successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update Fleet: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($farm_id, $id)
    {
        DB::beginTransaction();

        try {
            $fleet = QurbanFleet::findOrFail($id);

            $fleet->delete();

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(null, 'Fleet deleted successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to delete Fleet: ' . $e->getMessage(), 500);
        }
    }
}

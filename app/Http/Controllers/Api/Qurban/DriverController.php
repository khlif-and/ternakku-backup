<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\QurbanDriver;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Qurban\DriverService;
use App\Http\Resources\Qurban\DriverResource;
use App\Http\Requests\Qurban\DriverStoreRequest;
use App\Http\Requests\Qurban\DriverUpdateRequest;

class DriverController extends Controller
{
    private $driverService;

    public function __construct(DriverService $driverService)
    {
        $this->driverService = $driverService;
    }

    public function store(DriverStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $photo = null;

            // Handle logo upload if present
            if (isset($validated['photo']) && $request->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-photo-' . $file->getClientOriginalName();
                $filePath = 'drivers/photos/';
                $photo = uploadNeoObject($file, $fileName, $filePath);
            }

            // Simpan data ke tabel drivers
            $driver = QurbanDriver::create([
                'name'              => $validated['name'],
                'farm_id'          => $farm_id,
                'region_id'    => $validated['region_id'],
                'postal_code'  => $validated['postal_code'] ?? '',
                'address_line' => $validated['address_line'],
                'longitude'    => $validated['longitude'],
                'latitude'    => $validated['latitude'],
                'photo'            => $photo,
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new DriverResource($driver), 'Driver created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create driver: ' . $e->getMessage(), 500);
        }
    }

    public function show($farmId, $id)
    {
        $driver = QurbanDriver::findOrFail($id);

        return ResponseHelper::success(new DriverResource($driver), 'Driver found', 200);
    }

    public function index($farmId)
    {
        $drivers = $this->driverService->getDrivers($farmId);

        return ResponseHelper::success(DriverResource::collection($drivers), 'Drivers found', 200);
    }

    public function update(DriverUpdateRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $driver = QurbanDriver::findOrFail($id);

            $photo = $driver->photo;

            // Handle logo upload if present
            if (isset($validated['photo']) && $request->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-photo-' . $file->getClientOriginalName();
                $filePath = 'drivers/photos/';
                $photo = uploadNeoObject($file, $fileName, $filePath);
            }

            // Simpan data ke tabel drivers
            $driver->update([
                'name'              => $validated['name'],
                'region_id'    => $validated['region_id'],
                'postal_code'  => $validated['postal_code'] ?? '',
                'address_line' => $validated['address_line'],
                'longitude'    => $validated['longitude'],
                'latitude'    => $validated['latitude'],
                'photo'            => $photo,
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new DriverResource($driver), 'Driver updated successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update driver: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($farm_id, $id)
    {
        $driver = QurbanDriver::findOrFail($id);

        $driver->delete();

        return ResponseHelper::success(null, 'Driver deleted successfully', 200);
    }
}

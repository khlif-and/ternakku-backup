<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\QurbanDriver;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Qurban\DriverResource;
use App\Http\Requests\Qurban\DriverStoreRequest;

class DriverController extends Controller
{
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
}

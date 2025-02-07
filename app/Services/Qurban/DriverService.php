<?php

namespace App\Services\Qurban;

use App\Models\QurbanDriver;


class DriverService
{
    public function getDrivers($farmId)
    {
        $customers = QurbanDriver::where('farm_id', $farmId)->get();

        return $customers;
    }

    public function getDriver($farmId , $driverId)
    {
        $driver = QurbanDriver::where('farm_id', $farmId)->where('id' , $driverId)->first();

        return $driver;
    }

    public function storeDriver($farmId, $request)
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

            $data = $driver;
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

    public function updateDriver($farmId, $driverId, $request)
    {
        $validated = $request->validated();

        $error = false;
        $data = null;

        DB::beginTransaction();

        try {
            $driver = QurbanDriver::where('farm_id' , $farmId)->where('id' , $driverId)->first();

            $photo = null;

            // Handle logo upload if present
            if (isset($validated['photo']) && $request->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-photo-' . $file->getClientOriginalName();
                $filePath = 'drivers/photos/';
                $photo = uploadNeoObject($file, $fileName, $filePath);

                if ($driver->photo) {
                    deleteNeoObject($driver->photo);
                }
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

            DB::commit();

            $data = $driver;

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

    public function deleteDriver($farm_id, $driverId)
    {
        $error = false;

        try {
            $driver = QurbanDriver::where('farm_id' , $farm_id)->where('id',$driverId)->first();

            if ($driver->photo) {
                deleteNeoObject($driver->photo);
            }

            $driver->delete();

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

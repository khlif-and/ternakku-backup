<?php

namespace App\Services\Web\Farming\Reweight;

use App\Models\Livestock;
use App\Models\LivestockReweightD;
use App\Models\LivestockReweightH;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReweightCoreService
{
    public function store($farm, array $data)
    {
        DB::beginTransaction();

        try {
            // Find the livestock record
            $livestock = Livestock::find($data['livestock_id']);

            // Check if the livestock exists
            if (!$livestock) {
                throw new \Exception('Livestock not found.');
            }

            // Check if the livestock is already deceased
            if ($livestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
                throw new \Exception('This livestock is not active/alive.');
            }

            // Simpan data header LivestockReweightH
            $livestockReweightH = LivestockReweightH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $data['transaction_date'],
                'notes'            => $data['notes'] ?? null,
            ]);

            $photo = null;

            // Handle file upload if present
            if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $data['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'reweights/';
                $photo = uploadNeoObject($file, $fileName, $filePath);
            }

            $livestockReweightD = LivestockReweightD::create([
                'livestock_reweight_h_id' => $livestockReweightH->id,
                'livestock_id'            => $data['livestock_id'],
                'weight'                  => $data['weight'],
                'photo'                   => $photo,
            ]);

            DB::commit();

            return $livestockReweightD;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing reweight: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($farm, $id, array $data)
    {
        DB::beginTransaction();

        try {
            $livestockReweightD = LivestockReweightD::whereHas('livestockReweightH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id);
            })->findOrFail($id);

            $livestockReweightH = $livestockReweightD->livestockReweightH;

            $livestockReweightH->update([
                'transaction_date' => $data['transaction_date'],
                'notes'            => $data['notes'] ?? null,
            ]);

            $updateData = [
                'livestock_id' => $data['livestock_id'],
                'weight'       => $data['weight'],
            ];

            // Handle file upload if present
            if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $data['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'reweights/';

                // Delete the old photo if it exists
                if ($livestockReweightD->photo) {
                    deleteNeoObject($livestockReweightD->photo);
                }

                $updateData['photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            $livestockReweightD->update($updateData);

            DB::commit();

            return $livestockReweightD;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating reweight: ' . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($farm, $id)
    {
        DB::beginTransaction();

        try {
            $livestockReweightD = LivestockReweightD::whereHas('livestockReweightH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id);
            })->findOrFail($id);

            // Hapus foto jika ada
            if ($livestockReweightD->photo) {
                deleteNeoObject($livestockReweightD->photo);
            }

            $livestockReweightH = $livestockReweightD->livestockReweightH;

            $livestockReweightD->delete();

            // Cek apakah Header masih memiliki Detail lain
            if ($livestockReweightH->livestockReweightD()->count() === 0) {
                $livestockReweightH->delete();
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting reweight: ' . $e->getMessage());
            throw $e;
        }
    }

    public function get($farm, $id)
    {
        return LivestockReweightD::with(['livestock', 'livestockReweightH'])
            ->whereHas('livestockReweightH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id);
            })->findOrFail($id);
    }
}

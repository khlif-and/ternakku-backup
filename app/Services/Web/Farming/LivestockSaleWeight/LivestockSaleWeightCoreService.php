<?php

namespace App\Services\Web\Farming\LivestockSaleWeight;

use App\Models\Livestock;
use App\Models\LivestockSaleWeightD;
use App\Models\LivestockSaleWeightH;
use App\Enums\LivestockStatusEnum;

class LivestockSaleWeightCoreService
{
    public function listSaleWeights($farm, $filters)
    {
        $saleWeights = LivestockSaleWeightD::with(['livestock', 'livestockSaleWeightH'])
            ->whereHas('livestock')
            ->whereHas('livestockSaleWeightH', function ($query) use ($farm, $filters) {
                $query->where('farm_id', $farm->id);

                if (isset($filters['start_date']) && $filters['start_date']) {
                    $query->where('transaction_date', '>=', $filters['start_date']);
                }
                if (isset($filters['end_date']) && $filters['end_date']) {
                    $query->where('transaction_date', '<=', $filters['end_date']);
                }
                if (isset($filters['customer']) && $filters['customer']) {
                    $query->where('customer', 'like', '%' . $filters['customer'] . '%');
                }
            });

        if (isset($filters['livestock_type_id']) && $filters['livestock_type_id']) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('livestock_type_id', $filters['livestock_type_id']));
        }
        if (isset($filters['livestock_group_id']) && $filters['livestock_group_id']) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('livestock_group_id', $filters['livestock_group_id']));
        }
        if (isset($filters['livestock_breed_id']) && $filters['livestock_breed_id']) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('livestock_breed_id', $filters['livestock_breed_id']));
        }
        if (isset($filters['livestock_sex_id']) && $filters['livestock_sex_id']) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('livestock_sex_id', $filters['livestock_sex_id']));
        }
        if (isset($filters['pen_id']) && $filters['pen_id']) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('pen_id', $filters['pen_id']));
        }

        return $saleWeights->orderByDesc('id')->paginate(10);
    }

    public function storeSaleWeight($farm, array $data, $photoFile = null)
    {
        $livestock = Livestock::find($data['livestock_id']);

        if (!$livestock || $livestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
            throw new \Exception('Ternak tidak ditemukan atau sudah tidak hidup.');
        }

        $header = LivestockSaleWeightH::create([
            'farm_id' => $farm->id,
            'transaction_date' => $data['transaction_date'],
            'customer' => $data['customer'],
            'notes' => $data['notes'] ?? null,
        ]);

        $detailData = $data;
        $detailData['livestock_sale_weight_h_id'] = $header->id;
        
        // Remove header fields from detail data
        unset($detailData['customer'], $detailData['transaction_date']);

        if ($photoFile) {
            $fileName = time() . '-' . $photoFile->getClientOriginalName();
            $filePath = 'sale_weights/';
            $detailData['photo'] = uploadNeoObject($photoFile, $fileName, $filePath);
        }

        $saleWeight = LivestockSaleWeightD::create($detailData);

        $livestock->update(['livestock_status_id' => LivestockStatusEnum::TERJUAL->value]);

        return $saleWeight;
    }

    public function findSaleWeight($farm, $id)
    {
        return LivestockSaleWeightD::whereHas('livestockSaleWeightH', fn($q) => $q->where('farm_id', $farm->id))->findOrFail($id);
    }

    public function updateSaleWeight($farm, $id, array $data, $photoFile = null)
    {
        $saleWeight = $this->findSaleWeight($farm, $id);
        $header = $saleWeight->livestockSaleWeightH;

        $oldLivestockId = $saleWeight->livestock_id;

        if ($data['livestock_id'] != $oldLivestockId) {
            $newLivestock = Livestock::find($data['livestock_id']);
            if (!$newLivestock || $newLivestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
                throw new \Exception('Ternak baru tidak ditemukan atau sudah tidak hidup.');
            }
        }

        $header->update([
            'transaction_date' => $data['transaction_date'],
            'customer' => $data['customer'],
            'notes' => $data['notes'] ?? null,
        ]);

        $saleWeight->update([
            'livestock_id' => $data['livestock_id'],
            'weight' => $data['weight'],
            'price_per_kg' => $data['price_per_kg'],
            'price_per_head' => $data['price_per_head'],
            'notes' => $data['notes'] ?? $saleWeight->notes,
        ]);

        if ($photoFile) {
            if ($saleWeight->photo)
                deleteNeoObject($saleWeight->photo);

            $fileName = time() . '-' . $photoFile->getClientOriginalName();
            $filePath = 'livestock_sales/';
            $saleWeight->photo = uploadNeoObject($photoFile, $fileName, $filePath);
            $saleWeight->save();
        }

        if ($oldLivestockId !== $data['livestock_id']) {
            $old = Livestock::find($oldLivestockId);
            if ($old && $old->livestock_status_id === LivestockStatusEnum::TERJUAL->value) {
                $old->update(['livestock_status_id' => LivestockStatusEnum::HIDUP->value]);
            }

            $new = Livestock::find($data['livestock_id']);
            if ($new && $new->livestock_status_id !== LivestockStatusEnum::TERJUAL->value) {
                $new->update(['livestock_status_id' => LivestockStatusEnum::TERJUAL->value]);
            }
        }

        return $saleWeight;
    }

    public function deleteSaleWeight($farm, $id)
    {
        $saleWeight = $this->findSaleWeight($farm, $id);

        if ($saleWeight->photo)
            deleteNeoObject($saleWeight->photo);

        $livestock = Livestock::find($saleWeight->livestock_id);
        if ($livestock && $livestock->livestock_status_id === LivestockStatusEnum::TERJUAL->value) {
            $livestock->update(['livestock_status_id' => LivestockStatusEnum::HIDUP->value]);
        }

        $header = $saleWeight->livestockSaleWeightH;
        $saleWeight->delete();

        if ($header->livestockSaleWeightD()->count() === 0) {
            $header->delete();
        }
    }
}

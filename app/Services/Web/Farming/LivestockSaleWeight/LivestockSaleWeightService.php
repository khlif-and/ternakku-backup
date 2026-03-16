<?php

namespace App\Services\Web\Farming\LivestockSaleWeight;

use App\Models\Livestock;
use App\Enums\LivestockStatusEnum;

class LivestockSaleWeightService
{
    protected LivestockSaleWeightCoreService $core;

    public function __construct(LivestockSaleWeightCoreService $core)
    {
        $this->core = $core;
    }

    public function list($farm, $filters)
    {
        return $this->core->listSaleWeights($farm, $filters);
    }

    public function find($farm, $id)
    {
        return $this->core->findSaleWeight($farm, $id);
    }

    public function store($farm, array $data, $photoFile = null)
    {
        return $this->core->storeSaleWeight($farm, $data, $photoFile);
    }

    public function update($farm, $id, array $data, $photoFile = null)
    {
        return $this->core->updateSaleWeight($farm, $id, $data, $photoFile);
    }

    public function delete($farm, $id)
    {
        return $this->core->deleteSaleWeight($farm, $id);
    }

    public function getAliveLivestocks($farm)
    {
        return Livestock::with(['livestockType', 'livestockBreed'])
            ->where('farm_id', $farm->id)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->get();
    }

    public function getEditLivestocks($farm, $saleWeight)
    {
        return Livestock::where('farm_id', $farm->id)
            ->where(function ($q) use ($saleWeight) {
                $q->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                  ->orWhere('id', $saleWeight->livestock_id);
            })
            ->get();
    }
}


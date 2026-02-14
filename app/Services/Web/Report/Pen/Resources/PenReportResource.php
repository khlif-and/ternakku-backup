<?php

namespace App\Services\Web\Report\Pen\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenReportResource extends JsonResource
{
    public function toArray($request)
    {
        $occupancyRate = $this->capacity > 0 ? ($this->livestocks_count / $this->capacity) * 100 : 0;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'area' => $this->area,
            'capacity' => $this->capacity,
            'population' => $this->livestocks_count,
            'occupancy_rate' => round($occupancyRate, 2),
            'remaining_capacity' => max(0, $this->capacity - $this->livestocks_count),
            'status' => $this->getOccupancyStatus($occupancyRate),
        ];
    }

    private function getOccupancyStatus($rate)
    {
        if ($rate >= 100)
            return 'Penuh';
        if ($rate >= 80)
            return 'Hampir Penuh';
        if ($rate >= 50)
            return 'Terisi';
        return 'Tersedia';
    }
}

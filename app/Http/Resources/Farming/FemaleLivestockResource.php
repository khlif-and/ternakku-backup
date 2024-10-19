<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FemaleLivestockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'general' => new LivestockResource($this),
            'female_data' => [
                'insemination_number' => $this->insemination_number(),
                'artificial_insemination_number' => $this->artificial_insemination_number(),
                'natural_insemination_number' => $this->natural_insemination_number(),
                'pregnant_number' => $this->pregnant_number(),
                'children_number' => $this->children_number(),
            ]
        ];
    }
}

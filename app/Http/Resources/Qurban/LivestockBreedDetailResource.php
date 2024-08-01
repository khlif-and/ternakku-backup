<?php

namespace App\Http\Resources\Qurban;

use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Qurban\PartnerListResource;

class LivestockBreedDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_type_name' => $this->livestockType?->name,
            'name' => $this->name,
            'min_weight' => $this->min_weight,
            'max_weight' => $this->max_weight,
            'photo' => getNeoObject($this->photo),
            'description' => $this->description,
            'avaliable_on' => PartnerListResource::collection( $this->avaliableOn($this->id))
        ];
    }

    private function avaliableOn($livestockBreedId)
    {
        $farms = Farm::whereHas('livestockReceptionH.livestockReceptionD', function ($query) use ($livestockBreedId) {
            $query->where('livestock_breed_id', $livestockBreedId);
        })->get();

        return $farms;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\QurbanSaleLivestockD;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Farming\LivestockExpenseResource;

class LivestockResource extends JsonResource
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
            'farm_id' => $this->farm_id,
            'farm_name' => $this->farm->name,
            'eartag' => $this->eartag_number,
            'rfid' => $this->rfid_number,
            'reception_date' => $this->livestockReceptionD->livestockReceptionH->transaction_date,
            'dof' => $this->dof(),
            'reception_weight' => $this->livestockReceptionD->weight,
            'current_weight' => $this->last_weight,
            'current_age' => $this->current_age,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_type_name' => $this->livestockType->name,
            'livestock_group_id' => $this->livestock_group_id,
            'livestock_group_name' => $this->livestockGroup->name,
            'livestock_breed_id' => $this->livestock_breed_id,
            'livestock_breed_name' => $this->livestockBreed->name,
            'livestock_sex_id' => $this->livestock_sex_id,
            'livestock_sex_name' => $this->livestockSex->name,
            'livestock_classification_id' => $this->livestock_classification_id,
            'livestock_classifiacation_name' => $this->livestockClassification?->name,
            'bcs_number' => $this->bcs_number,
            'bcs_id' => $this->bcs_id,
            'bcs_name' => $this->bcs?->name,
            'pen_id' => $this->pen_id,
            'pen_name' => $this->pen->name,
            'current_photo' => $this->current_photo,
            'characteristic' => $this->characteristic,
            'qurban_price' => (float) $this->livestockReceptionD?->price_per_head,
            'sold_on_qurban' => $this->qurbanSaleLivestockD()->exists(),
            'expenses' => LivestockExpenseResource::collection($this->expenses),
        ];
    }
}

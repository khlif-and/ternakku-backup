<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentIndividuStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // You can add your authorization logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'livestock_id' => 'required|integer|exists:livestocks,id',
            'disease_id' => 'required|integer|exists:diseases,id',
            'total_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',

            // Validasi untuk array medicines
            'medicines' => 'required|array',
            'medicines.*.name' => 'required|string',
            'medicines.*.unit' => 'required|string',
            'medicines.*.qty_per_unit' => 'required|numeric|min:0',
            'medicines.*.price_per_unit' => 'required|numeric|min:0',
            'medicines.*.total_price' => 'required|numeric|min:0',

            // Validasi untuk array treatments
            'treatments' => 'required|array',
            'treatments.*.name' => 'required|string',
            'treatments.*.cost' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'transaction_date.required' => 'The transaction date is required.',
            'livestock_id.required' => 'The livestock ID is required.',
            'disease_id.required' => 'The disease ID is required.',
            'total_cost.required' => 'The total cost is required.',

            // Messages for medicines validation
            'medicines.required' => 'At least one medicine is required.',
            'medicines.*.name.required' => 'The name of the medicine is required.',
            'medicines.*.unit.required' => 'The unit of the medicine is required.',
            'medicines.*.qty_per_unit.required' => 'The quantity per unit is required.',
            'medicines.*.price_per_unit.required' => 'The price per unit is required.',
            'medicines.*.total_price.required' => 'The total price of the medicine is required.',

            // Messages for treatments validation
            'treatments.required' => 'At least one treatment is required.',
            'treatments.*.name.required' => 'The name of the treatment is required.',
            'treatments.*.cost.required' => 'The cost of the treatment is required.',
        ];
    }
}

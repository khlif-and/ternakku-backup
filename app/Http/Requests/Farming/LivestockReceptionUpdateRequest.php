<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class LivestockReceptionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $livestockReceptionId = $this->route('livestockReceptionId');

        return [
            'eartag_number' => 'required|string|max:255|unique:livestock_reception_d,eartag_number,' . $livestockReceptionId,
            'rfid_number' => 'nullable|string|max:255|unique:livestock_reception_d,rfid_number,' . $livestockReceptionId,
            'livestock_type_id' => 'required|exists:livestock_types,id',
            'livestock_group_id' => 'required|exists:livestock_groups,id',
            'livestock_breed_id' => 'required|exists:livestock_breeds,id',
            'livestock_sex_id' => 'required|exists:livestock_sexes,id',
            'pen_id' => 'required|exists:pens,id',
            'age_years' => 'required|integer|min:0',
            'age_months' => 'required|integer|min:0|max:11',
            'weight' => 'required|numeric|min:0|max:999999.99',
            'price_per_kg' => 'required|numeric|min:0|max:999999.99',
            'price_per_head' => 'required|numeric|min:0|max:999999999999.99',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'notes' => 'nullable|string|max:255',
            'supplier_id'             => 'required|exists:suppliers,id', // Tambahan validasi untuk supplier_id
            'transaction_date'        => 'required|date',
        ];
    }
}

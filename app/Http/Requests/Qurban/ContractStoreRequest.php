<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class ContractStoreRequest extends FormRequest
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
        return [
            'qurban_saving_registration_id' => 'required|exists:qurban_saving_registrations,id',
            'livestock_breed_id' => 'required|exists:livestock_breeds,id',
            'weight' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
            'region_id' => 'required|exists:regions,id',
            'postal_code' => 'required|string|max:10',
            'address_line' => 'nullable|string|max:255',
            'longitude' => 'nullable|numeric|between:-180,180',
            'latitude' => 'nullable|numeric|between:-90,90',
            'contract_date' => 'required|date',
            'down_payment' => 'required|numeric|min:0',
            'farm_id' => 'required|exists:farms,id',
            'estimated_delivery_date' => 'required|date|after_or_equal:contract_date',
        ];
    }
}

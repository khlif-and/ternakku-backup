<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class LivestockSaleWeightUpdateRequest extends FormRequest
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
            'transaction_date'     => 'required|date',
            'customer'             => 'required|string',
            'livestock_id'         => 'required|exists:livestocks,id',
            'weight'               => 'required|numeric|min:0|max:999999.99',
            'price_per_kg'         => 'required|numeric|min:0|max:999999.99',
            'price_per_head'       => 'required|numeric|min:0|max:999999999999.99',
            'photo'                => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'notes'                => 'nullable|string|max:255',
        ];
    }
}

<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class PriceUpdateRequest extends FormRequest
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
            'hijri_year'         => ['required', 'digits:4', 'integer', 'min:1300'],
            'livestock_type_id'  => ['required', 'exists:livestock_types,id'],
            'name'               => ['required', 'string', 'max:255'],
            'start_weight'       => ['required', 'numeric', 'min:0'],
            'end_weight'         => ['required', 'numeric', 'gt:start_weight'],
            'price_per_kg'       => ['required', 'numeric', 'min:0'],
        ];
    }
}

<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class SavingRegisterRequest extends FormRequest
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
            'livestock_breed_id' => 'required|exists:livestock_breeds,id',
            'farm_id' => 'required|exists:farms,id',
            'weight' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
            'postal_code' => 'required|string|max:10',
            'address_line' => 'nullable|string|max:255',
            'duration_months' => 'required|integer|min:1',
            'users' => 'required|array',
            'users.*.user_id' => 'required|exists:users,id',
            'users.*.bank_id' => 'required|exists:banks,id',
            'users.*.account_number' => 'required|string',
            'users.*.portion' => 'required|integer',
        ];
    }
}

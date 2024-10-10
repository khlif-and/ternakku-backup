<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class NaturalInseminationUpdateRequest extends FormRequest
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
            'transaction_date' => 'required|date',
            // 'livestock_id' => 'required|exists:livestocks,id',
            'action_time' => 'required|date_format:H:i',
            'sire_breed_id' => 'required|exists:livestock_breeds,id',
            'sire_owner_name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ];

    }
}

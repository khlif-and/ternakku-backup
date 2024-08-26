<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class LivestockDeathUpdateRequest extends FormRequest
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
            'livestock_id' => 'required|exists:livestocks,id',
            'diagnosis' => 'nullable|string',
            'indication' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}

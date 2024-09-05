<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class MilkProductionGlobalStoreRequest extends FormRequest
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
            'milking_shift' => 'required|in:morning,afternoon',
            'milking_time' => 'required|date_format:H:i',
            'milker_name' => 'required|string|max:255',
            'quantity_liters' => 'required|numeric|min:0',
            'milk_condition' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}

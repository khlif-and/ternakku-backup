<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class PregnantCheckUpdateRequest extends FormRequest
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
            'officer_name' => 'required|string|max:255',
            'action_time' => 'required|date_format:H:i',
            'status' => 'required|in:PREGNANT,NOT_PREGNANT',
            'pregnant_age' => 'nullable|integer|min:0',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ];

    }

    public function withValidator($validator)
    {
        $validator->sometimes('pregnant_age', 'required', function ($input) {
            return $input->status === 'PREGNANT';
        });
    }
}

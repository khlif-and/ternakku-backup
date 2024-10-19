<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class LivestockBirthStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'transaction_date' => 'required|date',
            'livestock_id' => 'required|exists:livestocks,id',
            'officer_name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:NORMAL,ABORTUS,PREMATURE',
            'estimated_weaning' => 'required|date',
            'notes' => 'nullable|string|max:1000',

            // Validation for Livestock Birth Details
            'details' => 'required_if:status,NORMAL,PREMATURE|array',
            'details.*.livestock_sex_id' => 'required|exists:livestock_sexes,id',
            'details.*.weight' => 'required|numeric|min:0',
            'details.*.value' => 'required|numeric|min:0',
            'details.*.birth_order' => 'required|integer|min:1',
            'details.*.status' => 'required|in:alive,dead',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->sometimes('details.*.offspring_value', 'required|numeric|min:0', function ($input) {
            // Check if any of the details have 'status' as 'alive'
            return collect($this->input('details'))->contains('status', 'alive');
        });

        $validator->sometimes('details.*.disease_id', 'required|exists:diseases,id', function ($input) {
            // Check if any of the details have 'status' as 'dead'
            return collect($this->input('details'))->contains('status', 'dead');
        });

        $validator->sometimes('details.*.indication', 'required|string|max:255', function ($input) {
            // Check if any of the details have 'status' as 'dead'
            return collect($this->input('details'))->contains('status', 'dead');
        });
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'details.*.offspring_value.required' => 'The offspring value is required when the status is alive.',
            'details.*.disease_id.required' => 'The disease ID is required when the status is dead.',
            'details.*.indication.required' => 'The indication is required when the status is dead.',
        ];
    }
}


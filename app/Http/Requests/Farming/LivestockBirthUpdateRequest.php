<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class LivestockBirthUpdateRequest extends FormRequest
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
            // 'livestock_id' => 'required|exists:livestocks,id',
            'officer_name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:NORMAL,ABORTUS,PREMATURE',
            'estimated_weaning' => 'required|date',
            'notes' => 'nullable|string|max:1000',

            // Validation for Livestock Birth Details
            'details' => 'required_if:status,NORMAL,PREMATURE|array',
            'details.*.livestock_sex_id' => 'required|exists:livestock_sexes,id',
            'details.*.livestock_breed_id' => 'required|exists:livestock_breeds,id',
            'details.*.weight' => 'required|numeric|min:0',
            'details.*.offspring_value' => 'nullable|numeric|min:0',
            'details.*.birth_order' => 'nullable|integer|min:1',
            'details.*.status' => 'nullable|in:alive,dead',
            'details.*.disease_id' => 'nullable|exists:diseases,id',
            'details.*.indication' => 'nullable|string',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $details = $this->input('details');

            if (is_array($details)) {
                foreach ($details as $index => $detail) {
                    // Jika status 'alive', offspring_value harus diisi
                    if (isset($detail['status']) && $detail['status'] === 'alive') {
                        if (!isset($detail['offspring_value']) || $detail['offspring_value'] === null) {
                            $validator->errors()->add("details.{$index}.offspring_value", 'The offspring value is required when the status is alive.');
                        }
                    }

                    // Jika status 'dead', disease_id dan indication harus diisi
                    if (isset($detail['status']) && $detail['status'] === 'dead') {
                        if (!isset($detail['disease_id']) || $detail['disease_id'] === null) {
                            $validator->errors()->add("details.{$index}.disease_id", 'The disease ID is required when the status is dead.');
                        }

                        if (!isset($detail['indication']) || $detail['indication'] === null) {
                            $validator->errors()->add("details.{$index}.indication", 'The indication is required when the status is dead.');
                        }
                    }
                }
            }
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


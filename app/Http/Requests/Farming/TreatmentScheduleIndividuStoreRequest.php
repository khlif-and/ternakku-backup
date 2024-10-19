<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentScheduleIndividuStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // You can add your authorization logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'schedule_date' => 'required|date',
            'livestock_id' => 'required|integer|exists:livestocks,id',
            'medicine_name' => 'nullable|string',
            'medicine_unit' => 'nullable|string',
            'medicine_qty_per_unit' => 'nullable|numeric|min:0',
            'treatment_name' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $medicineName = $this->input('medicine_name');
            $treatmentName = $this->input('treatment_name');

            // Ensure that at least one of the medicine_name or treatment_name is provided
            if (is_null($medicineName) && is_null($treatmentName)) {
                $validator->errors()->add('medicine_name', 'Either medicine name or treatment name must be provided.');
                $validator->errors()->add('treatment_name', 'Either medicine name or treatment name must be provided.');
            }
        });
    }
}

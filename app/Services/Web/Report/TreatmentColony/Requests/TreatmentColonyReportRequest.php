<?php

namespace App\Services\Web\Report\TreatmentColony\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentColonyReportRequest extends FormRequest
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'pen_id' => 'nullable|exists:pens,id',
            'farm_id' => 'required|exists:farms,id',
        ];
    }
}

<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class MilkAnalysisGlobalUpdateRequest extends FormRequest
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
            'bj' => 'nullable|numeric',
            'at' => 'nullable|boolean',
            'ab' => 'nullable|boolean',
            'mbrt' => 'nullable|numeric',
            'a_water' => 'nullable|numeric',
            'protein' => 'nullable|numeric',
            'fat' => 'nullable|numeric',
            'snf' => 'nullable|numeric',
            'ts' => 'nullable|numeric',
            'rzn' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ];
    }
}

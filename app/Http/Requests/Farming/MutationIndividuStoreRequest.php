<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class MutationIndividuStoreRequest extends FormRequest
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
            'transaction_date'        => 'required|date',
            'notes'                   => 'nullable|string',
            'livestock_id'      => 'required|exists:livestocks,id',
            'pen_destination'            => 'required|exists:pens,id',
        ];
    }
}

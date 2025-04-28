<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderStoreRequest extends FormRequest
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
            'order_date'            => 'required|date',
            'total_weight'          => 'required|numeric',
            'quantity'              => 'required|integer',
            'description'              => 'nullable|string',
            'customer_id'           => 'required|integer|exists:qurban_customers,id',
        ];
    }
}

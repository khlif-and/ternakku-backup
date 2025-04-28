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
            'customer_id'               => 'required|integer|exists:qurban_customers,id',
            'order_date'                    => 'required|date',
            'details'                     => 'required|array|min:1',
            'details.*.livestock_type_id' => 'required|integer|exists:livestock_types,id',
            'details.*.total_weight'      => 'required|numeric|min:1',
            'details.*.quantity'          => 'required|integer|min:1',
        ];
    }
}

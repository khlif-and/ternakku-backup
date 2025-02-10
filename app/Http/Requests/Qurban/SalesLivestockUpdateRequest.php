<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class SalesLivestockUpdateRequest extends FormRequest
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
            'customer_id'                   => 'required|exists:qurban_customers,id',
            'qurban_sales_order_id'         => 'nullable|exists:qurban_sales_orders,id',
            'transaction_date'              => 'required|date',
            'notes'                         => 'nullable|string',
            'details'                       => 'required|array',
            'details.*.customer_address_id' => 'required|exists:qurban_customer_addresses,id',
            'details.*.livestock_id'        => 'required|exists:livestocks,id',
            'details.*.min_weight'          => 'required|numeric|min:0',
            'details.*.max_weight'          => 'required|numeric|min:details.*.min_weight',
            'details.*.price_per_kg'        => 'required|numeric|min:0',
            'details.*.price_per_head'      => 'required|numeric|min:0',
        ];
    }
}

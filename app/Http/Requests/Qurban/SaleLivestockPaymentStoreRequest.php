<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class SaleLivestockPaymentStoreRequest extends FormRequest
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
            'qurban_customer_id' => 'required|exists:qurban_customers,id',
            'transaction_date' => 'required|date',
            'qurban_sale_livestock_id' => 'required|exists:qurban_sale_livestock_h,id',
            'amount' => 'required|numeric',
        ];
    }
}

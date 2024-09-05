<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class FeedMedicinePurchaseUpdateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'transaction_date' => ['required', 'date'],
            'supplier' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.purchase_type' => ['required', 'in:forage,concentrate,medicine'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.unit' => ['required', 'string', 'max:50'],
            'items.*.price_per_unit' => ['required', 'numeric', 'min:0'],
            'items.*.total_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'transaction_date.required' => 'Transaction date is required.',
            'transaction_date.date' => 'Transaction date must be a valid date.',
            'supplier.required' => 'Supplier is required.',
            'supplier.string' => 'Supplier must be a valid string.',
            'items.required' => 'At least one item is required.',
            'items.*.purchase_type.required' => 'Purchase type is required for each item.',
            'items.*.purchase_type.in' => 'Purchase type must be either feed or medicine.',
            'items.*.item_name.required' => 'Item name is required.',
            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.numeric' => 'Quantity must be a number.',
            'items.*.unit.required' => 'Unit is required.',
            'items.*.price_per_unit.required' => 'Price per unit is required.',
            'items.*.total_price.required' => 'Total price is required.',
        ];
    }
}

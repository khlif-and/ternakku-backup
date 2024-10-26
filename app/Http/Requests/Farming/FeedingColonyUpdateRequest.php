<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class FeedingColonyUpdateRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'transaction_date'        => 'required|date',
            'total_cost'              => 'required|numeric|min:0',
            'notes'                   => 'nullable|string',
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.type' => ['required', 'in:forage,concentrate,feed_material'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.qty_kg' => ['required', 'numeric', 'min:1'],
            'items.*.price_per_kg' => ['required', 'numeric', 'min:0'],
            'items.*.total_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'transaction_date.required' => 'Transaction date is required.',
            'transaction_date.date' => 'Transaction date must be a valid date.',

            // Additional fields
            'total_cost.required' => 'Total cost is required.',
            'total_cost.numeric' => 'Total cost must be a valid number.',
            'total_cost.min' => 'Total cost must be at least 0.',

            'notes.string' => 'Notes must be a valid string.',

            'items.required' => 'At least one item is required.',
            'items.array' => 'Items must be an array.',
            'items.min' => 'You must add at least one item.',

            'items.*.type.required' => 'Each item must have a type.',
            'items.*.type.in' => 'Item type must be one of the following: forage, concentrate, or feed_material.',

            'items.*.name.required' => 'Each item must have a name.',
            'items.*.name.string' => 'Item name must be a valid string.',
            'items.*.name.max' => 'Item name cannot exceed 255 characters.',

            'items.*.qty_kg.required' => 'Each item must have a quantity in kilograms.',
            'items.*.qty_kg.numeric' => 'Quantity must be a valid number.',
            'items.*.qty_kg.min' => 'Quantity must be at least 1 kilogram.',

            'items.*.price_per_kg.required' => 'Each item must have a price per kilogram.',
            'items.*.price_per_kg.numeric' => 'Price per kilogram must be a valid number.',
            'items.*.price_per_kg.min' => 'Price per kilogram must be at least 0.',

            'items.*.total_price.required' => 'Each item must have a total price.',
            'items.*.total_price.numeric' => 'Total price must be a valid number.',
            'items.*.total_price.min' => 'Total price must be at least 0.',
        ];
    }
}

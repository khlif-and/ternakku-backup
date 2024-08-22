<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class FeedingIndividuStoreRequest extends FormRequest
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
            'livestock_id'            => 'required|exists:livestocks,id',

            // Forage details
            'forage_name'             => 'required|string|max:255',
            'forage_qty_kg'           => 'required|numeric|min:0',
            'forage_price_kg'         => 'required|numeric|min:0',
            'forage_total'            => 'required|numeric|min:0',

            // Concentrate details
            'concentrate_name'        => 'required|string|max:255',
            'concentrate_qty_kg'      => 'required|numeric|min:0',
            'concentrate_price_kg'    => 'required|numeric|min:0',
            'concentrate_total'       => 'required|numeric|min:0',

            // Feed Feed Material details
            'feed_material_name'         => 'required|string|max:255',
            'feed_material_qty_kg'       => 'required|numeric|min:0',
            'feed_material_price_kg'     => 'required|numeric|min:0',
            'feed_material_total'        => 'required|numeric|min:0',

            // Additional fields
            'total_cost'              => 'required|numeric|min:0',
            'notes'                   => 'nullable|string',
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
            'livestock_id.required'          => 'Livestock ID is required.',
            'livestock_id.exists'            => 'The selected livestock ID does not exist.',

            'forage_name.required'           => 'Forage name is required.',
            'forage_qty_kg.required'         => 'Forage quantity (kg) is required.',
            'forage_price_kg.required'       => 'Forage price per kg is required.',
            'forage_total.required'          => 'Forage total is required.',

            'concentrate_name.required'      => 'Concentrate name is required.',
            'concentrate_qty_kg.required'    => 'Concentrate quantity (kg) is required.',
            'concentrate_price_kg.required'  => 'Concentrate price per kg is required.',
            'concentrate_total.required'     => 'Concentrate total is required.',

            'feed_material_name.required'       => 'Feed Material name is required.',
            'feed_material_qty_kg.required'     => 'Feed Material quantity (kg) is required.',
            'feed_material_price_kg.required'   => 'Feed Material price per kg is required.',
            'feed_material_total.required'      => 'Feed Material total is required.',

            'total_cost.required'            => 'Total cost is required.',
        ];
    }
}

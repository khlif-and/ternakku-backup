<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class FarmUpdateRequest extends FormRequest
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
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string|max:1000',
            'region_id'         => 'required|integer|exists:regions,id',
            'postal_code'       => 'required|string|max:10',
            'address_line'      => 'required|string|max:255',
            'longitude'         => 'nullable|numeric',
            'latitude'          => 'nullable|numeric',
            'capacity'          => 'nullable|integer|min:1',
            'logo'              => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'cover_photo'       => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ];
    }
}

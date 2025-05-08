<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class DriverStoreRequest extends FormRequest
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
            'region_id'         => 'required|integer|exists:regions,id',
            'postal_code'       => 'nullable|string|max:10',
            'address_line'      => 'required|string|max:255',
            'longitude'         => 'nullable|numeric',
            'latitude'          => 'nullable|numeric',
            'photo'              => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ];
    }
}

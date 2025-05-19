<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'longitude' => ['required', 'numeric'],
            'latitude' => ['required', 'numeric'],
        ];
    }
}

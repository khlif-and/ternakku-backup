<?php

namespace App\Http\Requests\Farming;

use Illuminate\Foundation\Http\FormRequest;

class FindUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username is required.',
            'username.string' => 'The username must be a string.',
        ];
    }
}


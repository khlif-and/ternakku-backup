<?php

namespace App\Services\Web\Report\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
        ];
    }
}

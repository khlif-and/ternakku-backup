<?php

namespace App\Services\Web\Report\Pen\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenReportRequest extends FormRequest
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

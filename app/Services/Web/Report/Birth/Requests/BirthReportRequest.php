<?php

namespace App\Services\Web\Report\Birth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BirthReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:alive,dead',
            'livestock_type_id' => 'nullable|exists:livestock_types,id',
            'livestock_breed_id' => 'nullable|exists:livestock_breeds,id',
            'pen_id' => 'nullable|exists:pens,id',
            'livestock_id' => 'nullable|exists:livestocks,id',
        ];
    }
}

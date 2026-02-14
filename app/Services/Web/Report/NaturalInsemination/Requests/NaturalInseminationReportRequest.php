<?php

namespace App\Services\Web\Report\NaturalInsemination\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NaturalInseminationReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'livestock_id' => 'nullable|exists:livestocks,id',
            'livestock_type_id' => 'nullable|exists:livestock_types,id',
            'livestock_group_id' => 'nullable|exists:livestock_groups,id',
            'livestock_breed_id' => 'nullable|exists:livestock_breeds,id',
            'pen_id' => 'nullable|exists:pens,id',
        ];
    }
}

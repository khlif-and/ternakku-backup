<?php

namespace App\Services\Web\Report\MutationIndividu\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MutationIndividuReportRequest extends FormRequest
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
            'livestock_id' => 'nullable|exists:livestocks,id',
        ];
    }
}

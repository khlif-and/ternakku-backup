<?php

namespace App\Services\Web\Report\MilkAnalysisIndividu\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MilkAnalysisIndividuReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'livestock_id' => 'nullable|exists:livestocks,id',
        ];
    }
}

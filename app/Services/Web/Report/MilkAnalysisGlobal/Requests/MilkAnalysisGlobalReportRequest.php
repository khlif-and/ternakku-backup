<?php

namespace App\Services\Web\Report\MilkAnalysisGlobal\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MilkAnalysisGlobalReportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}

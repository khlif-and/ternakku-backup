<?php

namespace App\Services\Web\Report\MilkProductionIndividu\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MilkProductionIndividuReportRequest extends FormRequest
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
        ];
    }
}

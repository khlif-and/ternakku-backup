<?php

namespace App\Services\Web\Report\QurbanPopulation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QurbanPopulationReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'livestock_type_id' => 'nullable|exists:livestock_types,id',
            'livestock_breed_id' => 'nullable|exists:livestock_breeds,id',
            'livestock_status_id' => 'nullable|exists:livestock_statuses,id',
        ];
    }
}

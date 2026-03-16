<?php

namespace App\Services\Web\Report\QurbanDeliveryOrder\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QurbanDeliveryOrderReportRequest extends FormRequest
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
            'qurban_customer_id' => 'nullable|exists:qurban_customers,id',
            'status' => 'nullable|string',
            'per_page' => 'nullable|integer',
        ];
    }
}

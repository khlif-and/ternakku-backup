<?php

namespace App\Services\Web\Report\SalesOrder\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderReportRequest extends FormRequest
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
            'qurban_customer_id' => 'nullable|exists:qurban_customers,id',
            'livestock_type_id' => 'nullable|exists:livestock_types,id',
        ];
    }
}

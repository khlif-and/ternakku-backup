<?php

namespace App\Services\Web\Report\QurbanPayment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QurbanPaymentReportRequest extends FormRequest
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
            'qurban_customer_id' => 'nullable|exists:qurban_customers,id',
        ];
    }
}

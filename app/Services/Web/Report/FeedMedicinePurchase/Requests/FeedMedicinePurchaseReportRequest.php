<?php

namespace App\Services\Web\Report\FeedMedicinePurchase\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedMedicinePurchaseReportRequest extends FormRequest
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
            'purchase_type' => 'nullable|in:forage,concentrate,medicine',
            'supplier' => 'nullable|string',
        ];
    }
}

<?php

namespace App\Services\Web\Report\FeedingColony\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedingColonyReportRequest extends FormRequest
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
            'pen_id' => 'nullable|exists:pens,id',
        ];
    }
}

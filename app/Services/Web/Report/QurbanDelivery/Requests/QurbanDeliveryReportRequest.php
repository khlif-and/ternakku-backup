<?php

namespace App\Services\Web\Report\QurbanDelivery\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QurbanDeliveryReportRequest extends FormRequest
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
            'driver_id' => 'nullable|exists:users,id',
            'fleet_id' => 'nullable|exists:qurban_fleets,id',
            'status' => 'nullable|in:pending,process,ready_to_deliver,on_delivery,delivered',
        ];
    }
}

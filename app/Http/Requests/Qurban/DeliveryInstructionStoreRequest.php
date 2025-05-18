<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryInstructionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_date' => 'required|date',
            'driver_id' => 'required|exists:users,id',
            'fleet_id' => 'required|exists:qurban_fleets,id',
            'delivery_order_ids' => 'required|array|min:1',
            'delivery_order_ids.*' => 'exists:qurban_delivery_order_h,id',
        ];
    }
}

<?php

namespace App\Http\Requests\Qurban;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
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

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('delivery_order_ids') && is_array($this->delivery_order_ids)) {
                foreach ($this->delivery_order_ids as $id) {
                    $exists = DB::table('qurban_delivery_instruction_d')
                        ->where('qurban_delivery_order_h_id', $id)
                        ->exists();

                    if ($exists) {
                        $validator->errors()->add(
                            'delivery_order_ids',
                            "Delivery order ID $id has already been scheduled."
                        );
                    }
                }
            }
        });
    }

}

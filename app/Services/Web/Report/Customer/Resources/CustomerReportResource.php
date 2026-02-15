<?php

namespace App\Services\Web\Report\Customer\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerReportResource extends JsonResource
{
    public function toArray($request)
    {
        $address = $this->addresses->first();
        $fullAddress = '-';

        if ($address) {
            $fullAddress = $address->address_line;
            if ($address->region) {
                $fullAddress .= ', ' . $address->region->village_name .
                    ', ' . $address->region->district_name .
                    ', ' . $address->region->regency_name .
                    ', ' . $address->region->province_name;
            }
            if ($address->postal_code) {
                $fullAddress .= ', ' . $address->postal_code;
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->user->name ?? '-',
            'phone_number' => $this->user->phone_number ?? '-',
            'email' => $this->user->email ?? '-',
            'address' => $fullAddress,
            'created_at' => $this->created_at,
        ];
    }
}

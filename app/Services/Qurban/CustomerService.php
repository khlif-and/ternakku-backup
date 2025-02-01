<?php

namespace App\Services\Qurban;

use App\Models\QurbanCustomer;

class CustomerService
{
    public function getCustomers($farmId)
    {
        $customers = QurbanCustomer::where('farm_id', $farmId)->get();

        return $customers;
    }
}

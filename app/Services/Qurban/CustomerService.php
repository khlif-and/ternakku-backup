<?php

namespace App\Services\Qurban;

use App\Models\QurbanCustomer;

class CustomerService
{
    public function getCustomers()
    {
        $customers = QurbanCustomer::all();

        return $customers;
    }
}

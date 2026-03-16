<?php

namespace App\Services\Web\Qurban\Payment;

class PaymentService
{
    protected PaymentCoreService $core;

    public function __construct(PaymentCoreService $core)
    {
        $this->core = $core;
    }

    public function find($id)
    {
        return $this->core->find($id);
    }
}

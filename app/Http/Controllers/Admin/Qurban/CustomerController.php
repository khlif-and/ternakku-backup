<?php

namespace App\Http\Controllers\Admin\Qurban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\CustomerService;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        //TODO : Get farm id from session
        $farmId = 1;

        $customers = $this->customerService->getCustomers($farmId);

        return view('admin.qurban.customer.index' , compact('customers'));
    }
}

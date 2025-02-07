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
        $farmId = session('selected_farm');

        $customers = $this->customerService->getCustomers($farmId);

        return view('admin.qurban.customer.index' , compact('customers'));
    }

    public function create()
    {
        return view('admin.qurban.customer.create');
    }

    public function store(Request $request)
    {
        $farmId = session('selected_farm');

        $response = $this->customerService->storeCustomer($validated, $farm_id);

        return redirect('qurban/customer');
    }

}

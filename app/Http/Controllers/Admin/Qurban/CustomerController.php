<?php

namespace App\Http\Controllers\Admin\Qurban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\CustomerService;
use App\Http\Requests\Qurban\CustomerStoreRequest;

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

    public function store(CustomerStoreRequest $request)
    {
        $validated = $request->validated();
        $farmId = session('selected_farm');

        $response = $this->customerService->storeCustomer($validated, $farmId);


        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while adding the customer');
        }

        return redirect('qurban/customer')->with('success', 'Customer added to the farm successfully');
    }

    public function edit($customerId)
    {
        $farmId = session('selected_farm');

        $customer = $this->customerService->getCustomer($farmId, $customerId);

        return view('admin.qurban.customer.edit' , compact('customer'));
    }

    public function update(CustomerStoreRequest $request, $customerId)
    {
        $validated = $request->validated();
        $farmId = session('selected_farm');

        $response = $this->customerService->updateCustomer($validated, $farmId, $customerId);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while updating the customer');
        }

        return redirect('qurban/customer')->with('success', 'Customer updated successfully');
    }

    public function destroy($customerId)
    {
        $farmId = session('selected_farm');

        $response = $this->customerService->deleteCustomer($farmId, $customerId);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while deleting the customer');
        }

        return redirect('qurban/customer')->with('success', 'Customer deleted successfully');
    }

}

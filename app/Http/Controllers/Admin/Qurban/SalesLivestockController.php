<?php

namespace App\Http\Controllers\Admin\Qurban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\SalesLivestockService;
use App\Http\Requests\Qurban\SalesLivestockStoreRequest;
use App\Http\Requests\Qurban\SalesLivestockUpdateRequest;
use App\Services\Qurban\CustomerService;

class SalesLivestockController extends Controller
{
    private $salesLivestockService, $customerService;

    public function __construct(SalesLivestockService $salesLivestockService, CustomerService $customerService)
    {
        $this->salesLivestockService = $salesLivestockService;
        $this->customerService = $customerService;
    }

    public function index()
    {
        $farmId = session("selected_farm");

        $salesLivestocks = $this->salesLivestockService->getSalesLivestocks($farmId);

        return view('admin.qurban.salesLivestock.index' , compact('salesLivestocks'));
    }

    public function create()
    {
        $farmId = session('selected_farm');

        $customers = $this->customerService->getCustomers($farmId);

        return view('admin.qurban.salesLivestock.create' , compact('customers'));
    }

    public function store(SalesLivestockStoreRequest $request)
    {
        $farmId = session('selected_farm');

        $response = $this->salesLivestockService->storeSalesLivestock($farmId,$request);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while adding the SalesLivestock');
        }

        return redirect('qurban/sales-order')->with('success', 'SalesLivestock added to the farm successfully');
    }

    public function edit($salesLivestockId)
    {
        $farmId = session('selected_farm');

        $customers = $this->customerService->getCustomers($farmId);

        $salesLivestock = $this->salesLivestockService->getSalesLivestock($farmId, $salesLivestockId);

        return view('admin.qurban.salesLivestock.edit' , compact('salesLivestock' , 'customers'));
    }

    public function update(SalesLivestockUpdateRequest $request, $salesLivestockId)
    {
        $farmId = session('selected_farm');

        $response = $this->salesLivestockService->updateSalesLivestock($farmId, $salesLivestockId, $request);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while updating the SalesLivestock');
        }

        return redirect('qurban/sales-order')->with('success', 'SalesLivestock updated successfully');
    }

    public function destroy($salesLivestockId)
    {
        $farmId = session('selected_farm');

        $response = $this->salesLivestockService->deleteSalesLivestock($farmId, $salesLivestockId);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while deleting the SalesLivestock');
        }

        return redirect('qurban/sales-order')->with('success', 'SalesLivestock deleted successfully');
    }
}

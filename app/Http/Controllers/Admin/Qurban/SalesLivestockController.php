<?php

namespace App\Http\Controllers\Admin\Qurban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\SalesOrderService;
use App\Http\Requests\Qurban\SalesOrderStoreRequest;
use App\Http\Requests\Qurban\SalesOrderUpdateRequest;
use App\Services\Qurban\CustomerService;

class SalesLivestockController extends Controller
{
    private $salesOrderService, $customerService;

    public function __construct(SalesOrderService $salesOrderService, CustomerService $customerService)
    {
        $this->salesOrderService = $salesOrderService;
        $this->customerService = $customerService;
    }

    public function index()
    {
        $farmId = session("selected_farm");

        $salesOrders = $this->salesOrderService->getSalesOrders($farmId);

        return view('admin.qurban.salesOrder.index' , compact('salesOrders'));
    }

    public function create()
    {
        $farmId = session('selected_farm');

        $customers = $this->customerService->getCustomers($farmId);

        return view('admin.qurban.salesOrder.create' , compact('customers'));
    }

    public function store(SalesLivestockStoreRequest $request)
    {
        $farmId = session('selected_farm');

        $response = $this->salesOrderService->storeSalesOrder($farmId,$request);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while adding the SalesOrder');
        }

        return redirect('qurban/sales-order')->with('success', 'SalesOrder added to the farm successfully');
    }

    public function edit($salesOrderId)
    {
        $farmId = session('selected_farm');

        $customers = $this->customerService->getCustomers($farmId);

        $salesOrder = $this->salesOrderService->getSalesOrder($farmId, $salesOrderId);

        return view('admin.qurban.salesOrder.edit' , compact('salesOrder' , 'customers'));
    }

    public function update(SalesOrderUpdateRequest $request, $salesOrderId)
    {
        $farmId = session('selected_farm');

        $response = $this->salesOrderService->updateSalesOrder($farmId, $salesOrderId, $request);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while updating the SalesOrder');
        }

        return redirect('qurban/sales-order')->with('success', 'SalesOrder updated successfully');
    }

    public function destroy($salesOrderId)
    {
        $farmId = session('selected_farm');

        $response = $this->salesOrderService->deleteSalesOrder($farmId, $salesOrderId);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while deleting the SalesOrder');
        }

        return redirect('qurban/sales-order')->with('success', 'SalesOrder deleted successfully');
    }
}

<?php

namespace App\Http\Controllers\Admin\Qurban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\SalesOrderService;
use App\Http\Requests\Qurban\SalesOrderStoreRequest;
use App\Http\Requests\Qurban\SalesOrderUpdateRequest;

class SalesOrderController extends Controller
{
    private $salesOrderService;

    public function __construct(SalesOrderService $salesOrderService)
    {
        $this->salesOrderService = $salesOrderService;
    }

    public function index()
    {
        $farmId = session("selected_farm");

        $salesOrders = $this->salesOrderService->getSalesOrders($farmId);

        return view('admin.qurban.salesOrder.index' , compact('SalesOrders'));
    }

    public function create()
    {
        return view('admin.qurban.salesOrder.create');
    }

    public function store(SalesOrderStoreRequest $request)
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

        $salesOrder = $this->salesOrderService->getSalesOrder($farmId, $salesOrderId);

        return view('admin.qurban.salesOrder.edit' , compact('SalesOrder'));
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

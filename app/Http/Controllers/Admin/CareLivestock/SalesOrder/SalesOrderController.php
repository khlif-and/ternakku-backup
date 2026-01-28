<?php

namespace App\Http\Controllers\Admin\CareLivestock\SalesOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CareLivestock\SalesOrder\SalesOrderStoreRequest;
use App\Http\Requests\Admin\CareLivestock\SalesOrder\SalesOrderUpdateRequest;
use App\Services\Qurban\SalesOrderService;

use App\Models\Farm;

class SalesOrderController extends Controller
{
    protected SalesOrderService $service;

    public function __construct(SalesOrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.qurban.sales_order.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.qurban.sales_order.create', compact('farm'));
    }

    public function store(SalesOrderStoreRequest $request, $farmId)
    {
        $result = $this->service->storeSalesOrder($farmId, $request);

        if ($result['error']) {
            return back()->with('error', 'Failed to create sales order');
        }

        return redirect()->route('admin.care-livestock.sales-order.index', $farmId)->with('success', 'Sales Order created successfully');
    }


    public function show($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        // We pass the ID to the view, Livewire component will fetch the model
        return view('admin.qurban.sales_order.show', compact('farm', 'id'));
    }

    public function edit($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        $salesOrder = $this->service->getSalesOrder($farmId, $id);
        
        return view('admin.qurban.sales_order.edit', compact('farm', 'salesOrder'));
    }

    public function update(SalesOrderUpdateRequest $request, $farmId, $id)
    {
        $result = $this->service->updateSalesOrder($farmId, $id, $request);

        if ($result['error']) {
            return back()->with('error', 'Failed to update sales order');
        }

        return redirect()->route('admin.care-livestock.sales-order.index', $farmId)->with('success', 'Sales Order updated successfully');
    }

    public function destroy($farmId, $id)
    {
        $result = $this->service->deleteSalesOrder($farmId, $id);

        if ($result['error']) {
            return back()->with('error', 'Failed to delete sales order');
        }

        return redirect()->route('admin.care-livestock.sales-order.index', $farmId)->with('success', 'Sales Order deleted successfully');
    }
}
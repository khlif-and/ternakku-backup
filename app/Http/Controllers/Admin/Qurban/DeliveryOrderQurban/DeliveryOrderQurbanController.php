<?php

namespace App\Http\Controllers\Admin\Qurban\DeliveryOrderQurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Qurban\DeliveryOrderQurban\QurbanDeliveryOrderService;

class DeliveryOrderQurbanController extends Controller
{
    protected QurbanDeliveryOrderService $service;

    public function __construct(QurbanDeliveryOrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function create()
    {
        return $this->service->create();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'qurban_sales_livestock_id' => 'required|integer|exists:qurban_sale_livestock_h,id',
            'transaction_date' => 'required|date',
        ]);

        return $this->service->store($validated);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function edit($id)
    {
        return $this->service->edit($id);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'delivery_schedule' => 'required|date',
        ]);

        return $this->service->updateSchedule($id, $validated['delivery_schedule']);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
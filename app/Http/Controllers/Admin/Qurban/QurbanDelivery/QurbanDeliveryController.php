<?php

namespace App\Http\Controllers\Admin\Qurban\QurbanDelivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryService;

class QurbanDeliveryController extends Controller
{
    protected QurbanDeliveryService $service;

    public function __construct(QurbanDeliveryService $service)
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
            'delivery_date' => 'required|date',
            'driver_id' => 'required|exists:users,id',
            'fleet_id' => 'required|exists:qurban_fleets,id',
            'delivery_order_ids' => 'required|array|min:1',
            'delivery_order_ids.*' => 'exists:qurban_delivery_order_h,id',
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
        return $this->service->setReadyToDeliver($id);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
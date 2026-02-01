<?php

namespace App\Http\Controllers\Admin\Qurban\QurbanDelivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Qurban\QurbanDeliveryStoreRequest;
use App\Http\Requests\Qurban\QurbanDeliveryUpdateRequest;
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

    public function store(QurbanDeliveryStoreRequest $request)
    {
        return $this->service->store($request);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function edit($id)
    {
        return $this->service->edit($id);
    }

    public function update(QurbanDeliveryUpdateRequest $request, $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
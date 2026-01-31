<?php

namespace App\Http\Controllers\Admin\Qurban\LivestockDeliveryQurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Qurban\LivestockDeliveryStoreRequest;
use App\Http\Requests\Qurban\LivestockDeliveryUpdateRequest;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteService;

class LivestockDeliveryController extends Controller
{
    protected LivestockDeliveryNoteService $service;

    public function __construct(LivestockDeliveryNoteService $service)
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

    public function store(LivestockDeliveryStoreRequest $request)
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

    public function update(LivestockDeliveryUpdateRequest $request, $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
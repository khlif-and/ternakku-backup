<?php

namespace App\Http\Controllers\Admin\CareLivestock\TreatmentLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\TreatmentColonyStoreRequest;
use App\Http\Requests\Farming\TreatmentColonyUpdateRequest;
use App\Services\Web\Farming\TreatmentColony\TreatmentColonyService;

class TreatmentColonyController extends Controller
{
    protected TreatmentColonyService $service;

    public function __construct(TreatmentColonyService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        return $this->service->index($farmId, $request);
    }

    public function create($farmId)
    {
        return $this->service->create($farmId);
    }

    public function store(TreatmentColonyStoreRequest $request, $farmId)
    {
        return $this->service->store($request, $farmId);
    }

    public function show($farmId, $id)
    {
        return $this->service->show($farmId, $id);
    }

    public function edit($farmId, $id)
    {
        return $this->service->edit($farmId, $id);
    }

    public function update(TreatmentColonyUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id)
    {
        return $this->service->destroy($farmId, $id);
    }
}

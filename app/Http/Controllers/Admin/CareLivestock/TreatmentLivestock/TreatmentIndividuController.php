<?php

namespace App\Http\Controllers\Admin\CareLivestock\TreatmentLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\TreatmentIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentIndividuUpdateRequest;
use App\Services\Web\Farming\TreatmentIndividu\TreatmentIndividuService;

class TreatmentIndividuController extends Controller
{
    protected TreatmentIndividuService $service;

    public function __construct(TreatmentIndividuService $service)
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

    public function store(TreatmentIndividuStoreRequest $request, $farmId)
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

    public function update(TreatmentIndividuUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id)
    {
        return $this->service->destroy($farmId, $id);
    }
}

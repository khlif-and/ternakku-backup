<?php

namespace App\Http\Controllers\Admin\CareLivestock\ColonyLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\TreatmentColonyStoreRequest;
use App\Http\Requests\Farming\TreatmentColonyUpdateRequest;
use App\Services\Web\Farming\ColonyLivestock\TreatmentColonyService;

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

    public function show($farmId, $treatmentColonyId)
    {
        return $this->service->show($farmId, $treatmentColonyId);
    }

    public function edit($farmId, $treatmentColonyId)
    {
        return $this->service->edit($farmId, $treatmentColonyId);
    }

    public function update(TreatmentColonyUpdateRequest $request, $farmId, $treatmentColonyId)
    {
        return $this->service->update($request, $farmId, $treatmentColonyId);
    }

    public function destroy($farmId, $treatmentColonyId)
    {
        return $this->service->destroy($farmId, $treatmentColonyId);
    }
}

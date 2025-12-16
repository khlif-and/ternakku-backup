<?php

namespace App\Http\Controllers\Admin\CareLivestock\TreatmentSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\TreatmentScheduleIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentScheduleIndividuUpdateRequest;
use App\Services\Web\Farming\TreatmentScheduleIndividu\TreatmentScheduleIndividuService;

class TreatmentScheduleIndividuController extends Controller
{
    protected TreatmentScheduleIndividuService $service;

    public function __construct(TreatmentScheduleIndividuService $service)
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
    public function store(TreatmentScheduleIndividuStoreRequest $request, $farmId)
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
    public function update(TreatmentScheduleIndividuUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }
    public function destroy($farmId, $id)
    {
        return $this->service->destroy($farmId, $id);
    }
}

<?php

namespace App\Http\Controllers\Admin\CareLivestock\MilkProductionIndividu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\MilkProductionIndividuStoreRequest;
use App\Http\Requests\Farming\MilkProductionIndividuUpdateRequest;
use App\Services\Web\Farming\MilkProductionIndividu\MilkProductionIndividuService;

class MilkProductionIndividuController extends Controller
{
    protected MilkProductionIndividuService $service;

    public function __construct(MilkProductionIndividuService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        return $this->service->index($farmId, $request);
    }

    public function create($farmId, Request $request)
    {
        return $this->service->create($farmId, $request);
    }

    public function store(MilkProductionIndividuStoreRequest $request, $farmId)
    {
        return $this->service->store($request, $farmId);
    }

    public function edit($farmId, $id, Request $request)
    {
        return $this->service->edit($farmId, $id, $request);
    }

    public function update(MilkProductionIndividuUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id, Request $request)
    {
        return $this->service->destroy($farmId, $id, $request);
    }
}

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

    public function create($farmId)
    {
        return $this->service->create($farmId);
    }

    public function store(MilkProductionIndividuStoreRequest $request, $farmId)
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

    public function update(MilkProductionIndividuUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id)
    {
        return $this->service->destroy($farmId, $id);
    }
}
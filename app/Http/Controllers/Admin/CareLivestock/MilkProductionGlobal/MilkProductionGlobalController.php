<?php

namespace App\Http\Controllers\Admin\CareLivestock\MilkProductionGlobal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\MilkProductionGlobalStoreRequest;
use App\Http\Requests\Farming\MilkProductionGlobalUpdateRequest;
use App\Services\Web\Farming\MilkProductionGlobal\MilkProductionGlobalService;

class MilkProductionGlobalController extends Controller
{
    protected MilkProductionGlobalService $service;

    public function __construct(MilkProductionGlobalService $service)
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

    public function store(MilkProductionGlobalStoreRequest $request, $farmId)
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

    public function update(MilkProductionGlobalUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id)
    {
        return $this->service->destroy($farmId, $id);
    }
}
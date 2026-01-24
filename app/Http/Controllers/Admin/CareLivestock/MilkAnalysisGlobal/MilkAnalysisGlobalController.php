<?php

namespace App\Http\Controllers\Admin\CareLivestock\MilkAnalysisGlobal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\MilkAnalysisGlobalStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisGlobalUpdateRequest;
use App\Services\Web\Farming\MilkAnalysisGlobal\MilkAnalysisGlobalService;

class MilkAnalysisGlobalController extends Controller
{
    protected MilkAnalysisGlobalService $service;

    public function __construct(MilkAnalysisGlobalService $service)
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

    public function store(MilkAnalysisGlobalStoreRequest $request, $farmId)
    {
        return $this->service->store($request, $farmId);
    }

    public function show($farmId, $id, Request $request)
    {
        return $this->service->show($farmId, $id, $request);
    }

    public function edit($farmId, $id, Request $request)
    {
        return $this->service->edit($farmId, $id, $request);
    }

    public function update(MilkAnalysisGlobalUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id, Request $request)
    {
        return $this->service->destroy($farmId, $id, $request);
    }
}
<?php

namespace App\Http\Controllers\Admin\CareLivestock\MilkAnalysisIndividu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\MilkAnalysisIndividuStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisIndividuUpdateRequest;
use App\Services\Web\Farming\MilkAnalysisIndividu\MilkAnalysisIndividuService;

class MilkAnalysisIndividuController extends Controller
{
    protected MilkAnalysisIndividuService $service;

    public function __construct(MilkAnalysisIndividuService $service)
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

    public function store(MilkAnalysisIndividuStoreRequest $request, $farmId)
    {
        return $this->service->store($request, $farmId);
    }

    public function edit($farmId, $id, Request $request)
    {
        return $this->service->edit($farmId, $id, $request);
    }

    public function update(MilkAnalysisIndividuUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id, Request $request)
    {
        return $this->service->destroy($farmId, $id, $request);
    }
}

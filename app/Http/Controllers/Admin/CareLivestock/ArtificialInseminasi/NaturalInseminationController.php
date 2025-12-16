<?php

namespace App\Http\Controllers\Admin\CareLivestock\ArtificialInseminasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\NaturalInseminationStoreRequest;
use App\Http\Requests\Farming\NaturalInseminationUpdateRequest;
use App\Services\Web\Farming\ArtificialInsemination\NaturalInseminationService;

class NaturalInseminationController extends Controller
{
    protected NaturalInseminationService $service;

    public function __construct(NaturalInseminationService $service)
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

    public function store(NaturalInseminationStoreRequest $request, $farmId)
    {
        return $this->service->store($request, $farmId);
    }

    public function show($farmId, $naturalInseminationId)
    {
        return $this->service->show($farmId, $naturalInseminationId);
    }

    public function edit($farmId, $naturalInseminationId)
    {
        return $this->service->edit($farmId, $naturalInseminationId);
    }

    public function update(NaturalInseminationUpdateRequest $request, $farmId, $naturalInseminationId)
    {
        return $this->service->update($request, $farmId, $naturalInseminationId);
    }

    public function destroy($farmId, $naturalInseminationId)
    {
        return $this->service->destroy($farmId, $naturalInseminationId);
    }
}

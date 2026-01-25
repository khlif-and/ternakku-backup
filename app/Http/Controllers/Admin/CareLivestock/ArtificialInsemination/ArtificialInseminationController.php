<?php

namespace App\Http\Controllers\Admin\CareLivestock\ArtificialInsemination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\ArtificialInseminationStoreRequest;
use App\Http\Requests\Farming\ArtificialInseminationUpdateRequest;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationService;

class ArtificialInseminationController extends Controller
{
    protected ArtificialInseminationService $service;

    public function __construct(ArtificialInseminationService $service)
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

    public function store(ArtificialInseminationStoreRequest $request, $farmId)
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

    public function update(ArtificialInseminationUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id)
    {
        return $this->service->destroy($farmId, $id);
    }
}
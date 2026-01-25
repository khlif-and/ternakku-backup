<?php

namespace App\Http\Controllers\Admin\CareLivestock\MutationLivestock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\MutationIndividuStoreRequest;
use App\Http\Requests\Farming\MutationIndividuUpdateRequest;
use App\Services\Web\Farming\MutationIndividu\MutationIndividuService;
use Illuminate\Http\Request;

class MutationIndividuController extends Controller
{
    protected MutationIndividuService $service;

    public function __construct(MutationIndividuService $service)
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

    public function store(MutationIndividuStoreRequest $request, $farmId)
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

    public function update(MutationIndividuUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id)
    {
        return $this->service->destroy($farmId, $id);
    }
}
<?php

namespace App\Http\Controllers\Admin\CareLivestock\MutationLivestock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\MutationIndividuStoreRequest;
use App\Http\Requests\Farming\MutationIndividuUpdateRequest;
use Illuminate\Http\Request;
use App\Services\Web\Farming\MutationIndividu\MutationIndividuService;

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

    public function show($farmId, $mutationIndividuId)
    {
        return $this->service->show($farmId, $mutationIndividuId);
    }

    public function edit($farmId, $mutationIndividuId)
    {
        return $this->service->edit($farmId, $mutationIndividuId);
    }

    public function update(MutationIndividuUpdateRequest $request, $farmId, $mutationIndividuId)
    {
        return $this->service->update($request, $farmId, $mutationIndividuId);
    }

    public function destroy($farmId, $mutationIndividuId)
    {
        return $this->service->destroy($farmId, $mutationIndividuId);
    }
}

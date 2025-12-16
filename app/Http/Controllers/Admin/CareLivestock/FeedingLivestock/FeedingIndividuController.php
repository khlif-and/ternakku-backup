<?php

namespace App\Http\Controllers\Admin\CareLivestock\FeedingLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\FeedingIndividuStoreRequest;
use App\Http\Requests\Farming\FeedingIndividuUpdateRequest;
use App\Services\Web\Farming\FeedingColony\FeedingIndividuService;

class FeedingIndividuController extends Controller
{
    protected FeedingIndividuService $service;

    public function __construct(FeedingIndividuService $service)
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

    public function store(FeedingIndividuStoreRequest $request, $farmId)
    {
        return $this->service->store($request, $farmId);
    }

    public function show($farmId, $feedingIndividuId)
    {
        return $this->service->show($farmId, $feedingIndividuId);
    }

    public function edit($farmId, $feedingIndividuId)
    {
        return $this->service->edit($farmId, $feedingIndividuId);
    }

    public function update(FeedingIndividuUpdateRequest $request, $farmId, $feedingIndividuId)
    {
        return $this->service->update($request, $farmId, $feedingIndividuId);
    }

    public function destroy($farmId, $feedingIndividuId)
    {
        return $this->service->destroy($farmId, $feedingIndividuId);
    }
}

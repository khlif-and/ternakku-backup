<?php

namespace App\Http\Controllers\Admin\CareLivestock\ColonyLivestock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\FeedingColonyStoreRequest;
use App\Http\Requests\Farming\FeedingColonyUpdateRequest;
use App\Services\Web\Farming\ColonyLivestock\FeedingColonyService;

class FeedingColonyController extends Controller
{
    protected FeedingColonyService $service;

    public function __construct(FeedingColonyService $service)
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

    public function store(FeedingColonyStoreRequest $request, $farmId)
    {
        return $this->service->store($request, $farmId);
    }

    public function show($farmId, $feedingColonyId)
    {
        return $this->service->show($farmId, $feedingColonyId);
    }

    public function edit($farmId, $feedingColonyId)
    {
        return $this->service->edit($farmId, $feedingColonyId);
    }

    public function update(FeedingColonyUpdateRequest $request, $farmId, $feedingColonyId)
    {
        return $this->service->update($request, $farmId, $feedingColonyId);
    }

    public function destroy($farmId, $feedingColonyId)
    {
        return $this->service->destroy($farmId, $feedingColonyId);
    }
}

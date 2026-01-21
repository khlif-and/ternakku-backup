<?php

namespace App\Http\Controllers\Admin\CareLivestock\FeedingLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\FeedingColony\FeedingColonyService;

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

    public function create($farmId, Request $request)
    {
        return $this->service->create($farmId, $request);
    }

    public function show($farmId, $feedingColonyId)
    {
        return $this->service->show($farmId, $feedingColonyId);
    }

    public function edit($farmId, $feedingColonyId)
    {
        return $this->service->edit($farmId, $feedingColonyId);
    }
}

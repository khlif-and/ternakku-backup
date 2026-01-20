<?php

namespace App\Http\Controllers\Admin\CareLivestock\FeedMedicinePurchase;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\FeedMedicinePurchaseStoreRequest;
use App\Http\Requests\Farming\FeedMedicinePurchaseUpdateRequest;
use App\Services\Web\Farming\FeedMedicinePurchase\FeedMedicinePurchaseService;

class FeedMedicinePurchaseController extends Controller
{
    private $service;

    public function __construct(FeedMedicinePurchaseService $service)
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

    public function store(FeedMedicinePurchaseStoreRequest $request, $farmId)
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

    public function update(FeedMedicinePurchaseUpdateRequest $request, $farmId, $id)
    {
        return $this->service->update($request, $farmId, $id);
    }

    public function destroy($farmId, $id, Request $request)
    {
        return $this->service->destroy($farmId, $id, $request);
    }
}

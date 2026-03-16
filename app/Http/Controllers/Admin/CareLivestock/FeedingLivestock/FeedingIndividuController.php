<?php

namespace App\Http\Controllers\Admin\CareLivestock\FeedingLivestock;

use App\Http\Controllers\Controller;
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

    public function index($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.feeding_individu.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.feeding_individu.create', compact('farm'));
    }

    public function store(FeedingIndividuStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');
        $feedingIndividu = $this->service->storeFeedingIndividu($farm, $request->validated());

        return redirect()
            ->route('admin.care-livestock.feeding-individu.show', [$farmId, $feedingIndividu->id])
            ->with('success', 'Data pemberian pakan individu berhasil ditambahkan.');
    }

    public function show($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $feedingIndividu = $this->service->findFeedingIndividu($farm, $feedingIndividuId);

        return view('admin.care_livestock.feeding_individu.show', compact('farm', 'feedingIndividu'));
    }

    public function edit($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $feedingIndividu = $this->service->findFeedingIndividu($farm, $feedingIndividuId);

        return view('admin.care_livestock.feeding_individu.edit', compact('farm', 'feedingIndividu'));
    }

    public function update(FeedingIndividuUpdateRequest $request, $farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $this->service->updateFeedingIndividu($farm, $feedingIndividuId, $request->validated());

        return redirect()
            ->route('admin.care-livestock.feeding-individu.show', [$farmId, $feedingIndividuId])
            ->with('success', 'Data pemberian pakan individu berhasil diperbarui.');
    }

    public function destroy($farmId, $feedingIndividuId)
    {
        $farm = request()->attributes->get('farm');
        $this->service->deleteFeedingIndividu($farm, $feedingIndividuId);

        return redirect()
            ->route('admin.care-livestock.feeding-individu.index', $farmId)
            ->with('success', 'Data pemberian pakan individu berhasil dihapus.');
    }
}

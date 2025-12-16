<?php

namespace App\Services\Web\Farming\ColonyLivestock;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\FeedingColonyStoreRequest;
use App\Http\Requests\Farming\FeedingColonyUpdateRequest;

class FeedingColonyService
{
    protected FeedingColonyCoreService $core;

    public function __construct(FeedingColonyCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $filters = $request->only(['start_date', 'end_date', 'pen_id']);

        $data = $this->core->listFeedings($farm, $filters);
        return view('admin.care_livestock.colony_livestock.feeding_colony.index', compact('farm', 'data'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.colony_livestock.feeding_colony.create', compact('farm'));
    }

    public function store(FeedingColonyStoreRequest $request, $farmId)
    {
        $farm = $request->attributes->get('farm');

        return ErrorHandler::handle(function () use ($farm, $request) {
            $this->core->storeFeeding($farm, $request->validated());
            return redirect()
                ->route('admin.care-livestock.feeding-colony.index', $farm->id)
                ->with('success', 'Data berhasil ditambahkan.');
        }, 'FeedingColony Store Error');
    }

    public function show($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColony = $this->core->findFeeding($farm, $feedingColonyId);
        return view('admin.care_livestock.colony_livestock.feeding_colony.show', compact('farm', 'feedingColony'));
    }

    public function edit($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColony = $this->core->findFeeding($farm, $feedingColonyId);
        return view('admin.care_livestock.colony_livestock.feeding_colony.edit', compact('farm', 'feedingColony'));
    }

    public function update(FeedingColonyUpdateRequest $request, $farmId, $feedingColonyId)
    {
        $farm = $request->attributes->get('farm');

        return ErrorHandler::handle(function () use ($farm, $feedingColonyId, $request) {
            $this->core->updateFeeding($farm, $feedingColonyId, $request->validated());
            return redirect()
                ->route('admin.care-livestock.feeding-colony.index', $farm->id)
                ->with('success', 'Data berhasil diupdate.');
        }, 'FeedingColony Update Error');
    }

    public function destroy($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');

        return ErrorHandler::handle(function () use ($farm, $feedingColonyId) {
            $this->core->deleteFeeding($farm, $feedingColonyId);
            return redirect()
                ->route('admin.care-livestock.feeding-colony.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        }, 'FeedingColony Delete Error');
    }
}

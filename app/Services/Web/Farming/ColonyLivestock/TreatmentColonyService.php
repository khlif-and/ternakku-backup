<?php

namespace App\Services\Web\Farming\ColonyLivestock;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\TreatmentColonyStoreRequest;
use App\Http\Requests\Farming\TreatmentColonyUpdateRequest;

class TreatmentColonyService
{
    protected TreatmentColonyCoreService $core;

    public function __construct(TreatmentColonyCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $filters = $request->only(['start_date', 'end_date', 'disease_id', 'pen_id']);
        $data = $this->core->listTreatments($farm, $filters);

        return view('admin.care_livestock.colony_livestock.treatment_colony.index', compact('farm', 'data'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.colony_livestock.treatment_colony.create', compact('farm'));
    }

    public function store(TreatmentColonyStoreRequest $request, $farmId)
    {
        $farm = $request->attributes->get('farm');

        return ErrorHandler::handle(function () use ($farm, $request) {
            $this->core->storeTreatment($farm, $request->validated());
            return redirect()
                ->route('admin.care-livestock.treatment-colony.index', $farm->id)
                ->with('success', 'Data berhasil ditambahkan.');
        }, 'TreatmentColony Store Error');
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->findTreatment($farm, $id);
        return view('admin.care_livestock.colony_livestock.treatment_colony.show', compact('farm', 'item'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->findTreatment($farm, $id);
        return view('admin.care_livestock.colony_livestock.treatment_colony.edit', compact('farm', 'item'));
    }

    public function update(TreatmentColonyUpdateRequest $request, $farmId, $id)
    {
        $farm = $request->attributes->get('farm');

        return ErrorHandler::handle(function () use ($farm, $id, $request) {
            $this->core->updateTreatment($farm, $id, $request->validated());
            return redirect()
                ->route('admin.care-livestock.treatment-colony.index', $farm->id)
                ->with('success', 'Data berhasil diupdate.');
        }, 'TreatmentColony Update Error');
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        return ErrorHandler::handle(function () use ($farm, $id) {
            $this->core->deleteTreatment($farm, $id);
            return redirect()
                ->route('admin.care-livestock.treatment-colony.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        }, 'TreatmentColony Delete Error');
    }
}

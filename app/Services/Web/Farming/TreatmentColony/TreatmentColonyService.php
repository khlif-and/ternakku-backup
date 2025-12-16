<?php

namespace App\Services\Web\Farming\TreatmentColony;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\TreatmentColonyStoreRequest;
use App\Http\Requests\Farming\TreatmentColonyUpdateRequest;
use App\Models\Disease;

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
        $items = $this->core->listTreatments($farm, $filters);

        return view('admin.care_livestock.treatment_colony.index', compact('farm', 'items', 'filters'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $pens = $farm->pens()->get();
        $diseases = Disease::all();

        return view('admin.care_livestock.treatment_colony.create', compact('farm', 'pens', 'diseases'));
    }

    public function store(TreatmentColonyStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $treatment = $this->core->storeTreatment($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-colony.show', [
                    'farm_id' => $farmId,
                    'id' => $treatment->id,
                ])
                ->with('success', 'Data created successfully');
        }, 'Create TreatmentColony Error');
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentColony = $this->core->findTreatment($farm, $id);

        return view('admin.care_livestock.treatment_colony.show', compact('farm', 'treatmentColony'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentColony = $this->core->findTreatment($farm, $id);
        $pens = $farm->pens()->get();
        $diseases = Disease::all();

        return view('admin.care_livestock.treatment_colony.edit', compact('farm', 'treatmentColony', 'pens', 'diseases'));
    }

    public function update(TreatmentColonyUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->updateTreatment($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-colony.show', [
                    'farm_id' => $farmId,
                    'id' => $id,
                ])
                ->with('success', 'Data updated successfully');
        }, 'Update TreatmentColony Error');
    }

    public function destroy($farmId, $id)
    {
        return ErrorHandler::handle(function () use ($farmId, $id) {
            $farm = request()->attributes->get('farm');
            $this->core->deleteTreatment($farm, $id);

            return redirect()
                ->route('admin.care-livestock.treatment-colony.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        }, 'Delete TreatmentColony Error');
    }
}

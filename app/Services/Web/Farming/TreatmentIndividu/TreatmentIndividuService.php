<?php

namespace App\Services\Web\Farming\TreatmentIndividu;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\TreatmentIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentIndividuUpdateRequest;
use App\Models\Disease;

class TreatmentIndividuService
{
    protected TreatmentIndividuCoreService $core;

    public function __construct(TreatmentIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $filters = $request->only([
            'start_date','end_date','disease_id','livestock_type_id',
            'livestock_group_id','livestock_breed_id','livestock_sex_id',
            'pen_id','livestock_id'
        ]);

        $items = $this->core->listTreatments($farm, $filters);
        return view('admin.care_livestock.treatment_individu.index', compact('farm', 'items', 'filters'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()->get();
        $diseases = Disease::all();

        return view('admin.care_livestock.treatment_individu.create', compact('farm','livestocks','diseases'));
    }

    public function store(TreatmentIndividuStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $treatment = $this->core->storeTreatment($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-individu.show', [
                    'farm_id' => $farmId,
                    'id' => $treatment->id,
                ])
                ->with('success', 'Data created successfully');
        }, 'Create TreatmentIndividu Error');
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentIndividu = $this->core->findTreatment($farm, $id);

        return view('admin.care_livestock.treatment_individu.show', compact('farm','treatmentIndividu'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentIndividu = $this->core->findTreatment($farm, $id);
        $livestocks = $farm->livestocks()->get();
        $diseases = Disease::all();

        return view('admin.care_livestock.treatment_individu.edit', compact('farm','treatmentIndividu','livestocks','diseases'));
    }

    public function update(TreatmentIndividuUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->updateTreatment($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-individu.show', [
                    'farm_id' => $farmId,
                    'id' => $id,
                ])
                ->with('success', 'Data updated successfully');
        }, 'Update TreatmentIndividu Error');
    }

    public function destroy($farmId, $id)
    {
        return ErrorHandler::handle(function () use ($farmId, $id) {
            $farm = request()->attributes->get('farm');
            $this->core->deleteTreatment($farm, $id);

            return redirect()
                ->route('admin.care-livestock.treatment-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        }, 'Delete TreatmentIndividu Error');
    }
}

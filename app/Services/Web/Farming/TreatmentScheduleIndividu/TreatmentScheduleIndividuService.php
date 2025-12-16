<?php

namespace App\Services\Web\Farming\TreatmentScheduleIndividu;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;
use App\Http\Requests\Farming\TreatmentScheduleIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentScheduleIndividuUpdateRequest;

class TreatmentScheduleIndividuService
{
    protected TreatmentScheduleIndividuCoreService $core;

    public function __construct(TreatmentScheduleIndividuCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $filters = $request->only([
            'start_date','end_date','livestock_id','livestock_type_id',
            'livestock_group_id','livestock_breed_id','livestock_sex_id','pen_id'
        ]);

        $items = $this->core->listSchedules($farm, $filters);
        return view('admin.care_livestock.treatment_schedule_individu.index', compact('farm', 'items', 'filters'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()->get();

        return view('admin.care_livestock.treatment_schedule_individu.create', compact('farm', 'livestocks'));
    }

    public function store(TreatmentScheduleIndividuStoreRequest $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $item = $this->core->storeSchedule($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.show', [
                    'farm_id' => $farmId,
                    'id' => $item->id,
                ])
                ->with('success', 'Data created successfully');
        }, 'Create TreatmentScheduleIndividu Error');
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentScheduleIndividu = $this->core->findSchedule($farm, $id);

        return view('admin.care_livestock.treatment_schedule_individu.show', compact('farm', 'treatmentScheduleIndividu'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentScheduleIndividu = $this->core->findSchedule($farm, $id);
        $livestocks = $farm->livestocks()->get();

        return view('admin.care_livestock.treatment_schedule_individu.edit', compact('farm', 'treatmentScheduleIndividu', 'livestocks'));
    }

    public function update(TreatmentScheduleIndividuUpdateRequest $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $farm = $request->attributes->get('farm');
            $this->core->updateSchedule($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.show', [
                    'farm_id' => $farmId,
                    'id' => $id,
                ])
                ->with('success', 'Data updated successfully');
        }, 'Update TreatmentScheduleIndividu Error');
    }

    public function destroy($farmId, $id)
    {
        return ErrorHandler::handle(function () use ($farmId, $id) {
            $farm = request()->attributes->get('farm');
            $this->core->deleteSchedule($farm, $id);

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        }, 'Delete TreatmentScheduleIndividu Error');
    }
}

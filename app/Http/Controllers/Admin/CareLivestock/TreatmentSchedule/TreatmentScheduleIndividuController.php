<?php

namespace App\Http\Controllers\Admin\CareLivestock\TreatmentSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\TreatmentScheduleIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentScheduleIndividuUpdateRequest;
use App\Services\Web\Farming\TreatmentScheduleIndividu\TreatmentScheduleIndividuService;

class TreatmentScheduleIndividuController extends Controller
{
    protected TreatmentScheduleIndividuService $service;

    public function __construct(TreatmentScheduleIndividuService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $filters = $request->only([
            'start_date',
            'end_date',
            'livestock_id',
            'livestock_type_id',
            'livestock_group_id',
            'livestock_breed_id',
            'livestock_sex_id',
            'pen_id'
        ]);
        $items = $this->service->list($farm, $filters);

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
        $farm = request()->attributes->get('farm');

        try {
            $item = $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.show', [
                    'farm_id' => $farmId,
                    'id' => $item->id,
                ])
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentScheduleIndividu = $this->service->find($farm, $id);

        return view('admin.care_livestock.treatment_schedule_individu.show', compact('farm', 'treatmentScheduleIndividu'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentScheduleIndividu = $this->service->find($farm, $id);
        $livestocks = $farm->livestocks()->get();

        return view('admin.care_livestock.treatment_schedule_individu.edit', compact('farm', 'treatmentScheduleIndividu', 'livestocks'));
    }

    public function update(TreatmentScheduleIndividuUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.show', [
                    'farm_id' => $farmId,
                    'id' => $id,
                ])
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->delete($farm, $id);

            return redirect()
                ->route('admin.care-livestock.treatment-schedule-individu.index', ['farm_id' => $farmId])
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}


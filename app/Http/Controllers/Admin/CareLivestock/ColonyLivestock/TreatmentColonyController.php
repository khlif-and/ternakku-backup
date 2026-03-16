<?php

namespace App\Http\Controllers\Admin\CareLivestock\ColonyLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\TreatmentColonyStoreRequest;
use App\Http\Requests\Farming\TreatmentColonyUpdateRequest;
use App\Services\Web\Farming\ColonyLivestock\TreatmentColonyService;

class TreatmentColonyController extends Controller
{
    protected TreatmentColonyService $service;

    public function __construct(TreatmentColonyService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $filters = $request->only(['start_date', 'end_date', 'disease_id', 'pen_id']);
        $data = $this->service->list($farm, $filters);

        return view('admin.care_livestock.colony_livestock.treatment_colony.index', compact('farm', 'data'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.colony_livestock.treatment_colony.create', compact('farm'));
    }

    public function store(TreatmentColonyStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-colony.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->service->find($farm, $treatmentColonyId);

        return view('admin.care_livestock.colony_livestock.treatment_colony.show', compact('farm', 'item'));
    }

    public function edit($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->service->find($farm, $treatmentColonyId);

        return view('admin.care_livestock.colony_livestock.treatment_colony.edit', compact('farm', 'item'));
    }

    public function update(TreatmentColonyUpdateRequest $request, $farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $treatmentColonyId, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-colony.index', $farmId)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($farmId, $treatmentColonyId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->delete($farm, $treatmentColonyId);

            return redirect()
                ->route('admin.care-livestock.treatment-colony.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}


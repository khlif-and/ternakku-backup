<?php

namespace App\Http\Controllers\Admin\CareLivestock\TreatmentLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\TreatmentIndividu\TreatmentIndividuService;
use App\Http\Requests\Farming\TreatmentIndividuStoreRequest;
use App\Http\Requests\Farming\TreatmentIndividuUpdateRequest;

class TreatmentIndividuController extends Controller
{
    protected TreatmentIndividuService $service;

    public function __construct(TreatmentIndividuService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.treatment_individu.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.treatment_individu.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentIndividu = $this->service->find($farm, $id);

        return view('admin.care_livestock.treatment_individu.show', compact('farm', 'treatmentIndividu'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $treatmentIndividu = $this->service->find($farm, $id);

        return view('admin.care_livestock.treatment_individu.edit', compact('farm', 'treatmentIndividu'));
    }

    public function store(TreatmentIndividuStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-individu.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(TreatmentIndividuUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.treatment-individu.show', [$farmId, $id])
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
                ->route('admin.care-livestock.treatment-individu.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

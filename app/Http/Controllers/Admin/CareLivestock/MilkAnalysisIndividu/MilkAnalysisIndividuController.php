<?php

namespace App\Http\Controllers\Admin\CareLivestock\MilkAnalysisIndividu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\MilkAnalysisIndividu\MilkAnalysisIndividuService;
use App\Http\Requests\Farming\MilkAnalysisIndividuStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisIndividuUpdateRequest;

class MilkAnalysisIndividuController extends Controller
{
    protected MilkAnalysisIndividuService $service;

    public function __construct(MilkAnalysisIndividuService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_analysis_individu.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()->where('livestock_sex_id', 2)->get();


        return view('admin.care_livestock.milk_analysis_individu.create', compact('farm', 'livestocks'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkAnalysisIndividu = $this->service->find($farm, $id);

        return view('admin.care_livestock.milk_analysis_individu.show', compact('farm', 'milkAnalysisIndividu'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkAnalysisIndividu = $this->service->find($farm, $id);
        $livestocks = $farm->livestocks()->where('livestock_sex_id', 2)->get();


        return view('admin.care_livestock.milk_analysis_individu.edit', compact('farm', 'milkAnalysisIndividu', 'livestocks'));
    }

    public function store(MilkAnalysisIndividuStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-analysis-individu.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(MilkAnalysisIndividuUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-analysis-individu.show', [$farmId, $id])
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
                ->route('admin.care-livestock.milk-analysis-individu.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin\CareLivestock\MilkAnalysisGlobal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\MilkAnalysisGlobal\MilkAnalysisGlobalService;
use App\Http\Requests\Farming\MilkAnalysisGlobalStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisGlobalUpdateRequest;

class MilkAnalysisGlobalController extends Controller
{
    protected MilkAnalysisGlobalService $service;

    public function __construct(MilkAnalysisGlobalService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_analysis_global.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_analysis_global.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkAnalysisGlobal = $this->service->find($farm, $id);

        return view('admin.care_livestock.milk_analysis_global.show', compact('farm', 'milkAnalysisGlobal'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkAnalysisGlobal = $this->service->find($farm, $id);

        return view('admin.care_livestock.milk_analysis_global.edit', compact('farm', 'milkAnalysisGlobal'));
    }

    public function store(MilkAnalysisGlobalStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-analysis-global.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(MilkAnalysisGlobalUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-analysis-global.show', [$farmId, $id])
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
                ->route('admin.care-livestock.milk-analysis-global.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

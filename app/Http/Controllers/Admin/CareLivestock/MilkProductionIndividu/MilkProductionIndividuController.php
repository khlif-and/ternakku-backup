<?php

namespace App\Http\Controllers\Admin\CareLivestock\MilkProductionIndividu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\MilkProductionIndividu\MilkProductionIndividuService;
use App\Http\Requests\Farming\MilkProductionIndividuStoreRequest;
use App\Http\Requests\Farming\MilkProductionIndividuUpdateRequest;

class MilkProductionIndividuController extends Controller
{
    protected MilkProductionIndividuService $service;

    public function __construct(MilkProductionIndividuService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_production_individu.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_production_individu.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkProductionIndividu = $this->service->find($farm, $id);

        return view('admin.care_livestock.milk_production_individu.show', compact('farm', 'milkProductionIndividu'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkProductionIndividu = $this->service->find($farm, $id);

        return view('admin.care_livestock.milk_production_individu.edit', compact('farm', 'milkProductionIndividu'));
    }

    public function store(MilkProductionIndividuStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-production-individu.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(MilkProductionIndividuUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-production-individu.show', [$farmId, $id])
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
                ->route('admin.care-livestock.milk-production-individu.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

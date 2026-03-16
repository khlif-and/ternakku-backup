<?php

namespace App\Http\Controllers\Admin\CareLivestock\MilkProductionGlobal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\MilkProductionGlobal\MilkProductionGlobalService;
use App\Http\Requests\Farming\MilkProductionGlobalStoreRequest;
use App\Http\Requests\Farming\MilkProductionGlobalUpdateRequest;

class MilkProductionGlobalController extends Controller
{
    protected MilkProductionGlobalService $service;

    public function __construct(MilkProductionGlobalService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_production_global.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.milk_production_global.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkProductionGlobal = $this->service->find($farm, $id);

        return view('admin.care_livestock.milk_production_global.show', compact('farm', 'milkProductionGlobal'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $milkProductionGlobal = $this->service->find($farm, $id);

        return view('admin.care_livestock.milk_production_global.edit', compact('farm', 'milkProductionGlobal'));
    }

    public function store(MilkProductionGlobalStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-production-global.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(MilkProductionGlobalUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.milk-production-global.show', [$farmId, $id])
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
                ->route('admin.care-livestock.milk-production-global.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

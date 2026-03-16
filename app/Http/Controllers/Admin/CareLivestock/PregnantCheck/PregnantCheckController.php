<?php

namespace App\Http\Controllers\Admin\CareLivestock\PregnantCheck;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\PregnantCheckStoreRequest;
use App\Http\Requests\Farming\PregnantCheckUpdateRequest;
use App\Services\Web\Farming\PregnantCheck\PregnantCheckService;

class PregnantCheckController extends Controller
{
    protected PregnantCheckService $service;

    public function __construct(PregnantCheckService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.pregnant_check.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.pregnant_check.create', compact('farm'));
    }

    public function store(PregnantCheckStoreRequest $request, $farmId)
    {
        $farm = $request->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()->route('admin.care_livestock.pregnant_check.index', $farmId)
                ->with('success', 'Data pemeriksaan kebuntingan berhasil disimpan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Gagal menyimpan data pemeriksaan kebuntingan.');
        }
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->service->find($farm, $id);

        return view('admin.care_livestock.pregnant_check.show', compact('farm', 'item'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->service->find($farm, $id);

        return view('admin.care_livestock.pregnant_check.edit', compact('farm', 'item'));
    }

    public function update(PregnantCheckUpdateRequest $request, $farmId, $id)
    {
        $farm = $request->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()->route('admin.care_livestock.pregnant_check.show', [$farmId, $id])
                ->with('success', 'Data pemeriksaan kebuntingan berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Gagal memperbarui data pemeriksaan kebuntingan.');
        }
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->delete($farm, $id);

            return redirect()->route('admin.care_livestock.pregnant_check.index', $farmId)
                ->with('success', 'Data pemeriksaan kebuntingan berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Gagal menghapus data pemeriksaan kebuntingan.');
        }
    }
}

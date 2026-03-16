<?php

namespace App\Http\Controllers\Admin\CareLivestock\NaturalInsemination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\NaturalInseminationStoreRequest;
use App\Http\Requests\Farming\NaturalInseminationUpdateRequest;
use App\Services\Web\Farming\NaturalInsemination\NaturalInseminationService;

class NaturalInseminationController extends Controller
{
    protected NaturalInseminationService $service;

    public function __construct(NaturalInseminationService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');

        return view('admin.care_livestock.natural_insemination.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.natural_insemination.create', compact('farm'));
    }

    public function store(NaturalInseminationStoreRequest $request, $farmId)
    {
        $farm = $request->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()->route('admin.care-livestock.natural-insemination.index', $farmId)
                ->with('success', 'Data inseminasi alami berhasil disimpan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Gagal menyimpan data inseminasi alami.');
        }
    }

    public function show($farmId, $naturalInseminationId)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->service->find($farm, $naturalInseminationId);

        return view('admin.care_livestock.natural_insemination.show', compact('farm', 'item'));
    }

    public function edit($farmId, $naturalInseminationId)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->service->find($farm, $naturalInseminationId);

        return view('admin.care_livestock.natural_insemination.edit', compact('farm', 'item'));
    }

    public function update(NaturalInseminationUpdateRequest $request, $farmId, $naturalInseminationId)
    {
        $farm = $request->attributes->get('farm');

        try {
            $this->service->update($farm, $naturalInseminationId, $request->validated());

            return redirect()->route('admin.care-livestock.natural-insemination.show', [$farmId, $naturalInseminationId])
                ->with('success', 'Data inseminasi alami berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Gagal memperbarui data inseminasi alami.');
        }
    }

    public function destroy($farmId, $naturalInseminationId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $item = $this->service->find($farm, $naturalInseminationId);
            $this->service->delete($item);

            return redirect()->route('admin.care-livestock.natural-insemination.index', $farmId)
                ->with('success', 'Data inseminasi alami berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Gagal menghapus data inseminasi alami.');
        }
    }
}

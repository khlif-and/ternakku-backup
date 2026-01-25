<?php

namespace App\Services\Web\Farming\NaturalInsemination;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NaturalInseminationService
{
    protected NaturalInseminationCoreService $core;

    public function __construct(NaturalInseminationCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.natural_insemination.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.natural_insemination.create', compact('farm'));
    }

    public function store($request, $farmId)
    {
        $farm = request()->attributes->get('farm');
        try {
            $this->core->store($farm, $request->validated());
            return redirect()->route('admin.care-livestock.natural-insemination.index', $farmId)
                ->with('success', 'Data inseminasi alami berhasil disimpan.');
        } catch (\Throwable $e) {
            Log::error('Natural Insemination store error: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        try {
            $item = $this->core->find($farm, $id);
            return view('admin.care_livestock.natural_insemination.show', compact('farm', 'item'));
        } catch (\Throwable $e) {
            Log::error('Natural Insemination show error: ' . $e->getMessage());
            return back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        try {
            $item = $this->core->find($farm, $id);
            return view('admin.care_livestock.natural_insemination.edit', compact('farm', 'item'));
        } catch (\Throwable $e) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function update($request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        try {
            $this->core->update($farm, $id, $request->validated());
            return redirect()->route('admin.care-livestock.natural-insemination.show', [$farmId, $id])
                ->with('success', 'Data inseminasi alami berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Natural Insemination update error: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        try {
            $item = $this->core->find($farm, $id);
            $this->core->delete($item);
            return redirect()->route('admin.care-livestock.natural-insemination.index', $farmId)
                ->with('success', 'Data inseminasi alami berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Natural Insemination delete error: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}
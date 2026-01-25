<?php

namespace App\Services\Web\Farming\ArtificialInsemination;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArtificialInseminationService
{
    protected ArtificialInseminationCoreService $core;

    public function __construct(ArtificialInseminationCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.artificial_inseminasi.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.artificial_inseminasi.create', compact('farm'));
    }

    public function store($request, $farmId)
    {
        $farm = request()->attributes->get('farm');
        try {
            $this->core->store($farm, $request->validated());
            return redirect()->route('admin.care-livestock.artificial-inseminasi.index', $farmId)
                ->with('success', 'Data inseminasi berhasil disimpan.');
        } catch (\Throwable $e) {
            Log::error('AI store error: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        try {
            $item = $this->core->find($farm, $id);
            return view('admin.care_livestock.artificial_inseminasi.show', compact('farm', 'item'));
        } catch (\Throwable $e) {
            Log::error('AI show error: ' . $e->getMessage());
            return back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        try {
            $item = $this->core->find($farm, $id);
            return view('admin.care_livestock.artificial_inseminasi.edit', compact('farm', 'item'));
        } catch (\Throwable $e) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function update($request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        try {
            $this->core->update($farm, $id, $request->validated());
            return redirect()->route('admin.care-livestock.artificial-inseminasi.show', [$farmId, $id])
                ->with('success', 'Data inseminasi berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('AI update error: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        try {
            $item = $this->core->find($farm, $id);
            $this->core->delete($item);
            return redirect()->route('admin.care-livestock.artificial-inseminasi.index', $farmId)
                ->with('success', 'Data inseminasi berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('AI delete error: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}
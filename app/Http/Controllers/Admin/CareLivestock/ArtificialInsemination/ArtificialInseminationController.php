<?php

namespace App\Http\Controllers\Admin\CareLivestock\ArtificialInsemination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\ArtificialInseminationStoreRequest;
use App\Http\Requests\Farming\ArtificialInseminationUpdateRequest;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationService;

class ArtificialInseminationController extends Controller
{
    protected ArtificialInseminationService $service;

    public function __construct(ArtificialInseminationService $service)
    {
        $this->service = $service;
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

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->service->find($farm, $id);

        return view('admin.care_livestock.artificial_inseminasi.show', compact('farm', 'item'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->service->find($farm, $id);

        return view('admin.care_livestock.artificial_inseminasi.edit', compact('farm', 'item'));
    }

    public function store(ArtificialInseminationStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.artificial-inseminasi.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(ArtificialInseminationUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.artificial-inseminasi.show', [$farmId, $id])
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
                ->route('admin.care-livestock.artificial-inseminasi.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
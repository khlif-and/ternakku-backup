<?php

namespace App\Http\Controllers\Admin\CareLivestock\LivestockBirthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\LivestockBirth\LivestockBirthService;
use App\Http\Requests\Farming\LivestockBirthStoreRequest;
use App\Http\Requests\Farming\LivestockBirthUpdateRequest;

class LivestockBirthController extends Controller
{
    protected LivestockBirthService $service;

    public function __construct(LivestockBirthService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.livestock_birth.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.livestock_birth.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $birth = $this->service->find($farm, $id);

        return view('admin.care_livestock.livestock_birth.show', compact('farm', 'birth'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $birth = $this->service->find($farm, $id);

        return view('admin.care_livestock.livestock_birth.edit', compact('farm', 'birth'));
    }

    public function store(LivestockBirthStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care_livestock.livestock_birth.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(LivestockBirthUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care_livestock.livestock_birth.show', [$farmId, $id])
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
                ->route('admin.care_livestock.livestock_birth.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

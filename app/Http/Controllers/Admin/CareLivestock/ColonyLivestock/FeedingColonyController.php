<?php

namespace App\Http\Controllers\Admin\CareLivestock\ColonyLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\FeedingColonyStoreRequest;
use App\Http\Requests\Farming\FeedingColonyUpdateRequest;
use App\Services\Web\Farming\ColonyLivestock\FeedingColonyService;

class FeedingColonyController extends Controller
{
    protected FeedingColonyService $service;

    public function __construct(FeedingColonyService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $filters = $request->only(['start_date', 'end_date', 'pen_id']);
        $data = $this->service->list($farm, $filters);

        return view('admin.care_livestock.colony_livestock.feeding_colony.index', compact('farm', 'data'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.colony_livestock.feeding_colony.create', compact('farm'));
    }

    public function store(FeedingColonyStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.feeding-colony.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColony = $this->service->find($farm, $feedingColonyId);

        return view('admin.care_livestock.colony_livestock.feeding_colony.show', compact('farm', 'feedingColony'));
    }

    public function edit($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');
        $feedingColony = $this->service->find($farm, $feedingColonyId);

        return view('admin.care_livestock.colony_livestock.feeding_colony.edit', compact('farm', 'feedingColony'));
    }

    public function update(FeedingColonyUpdateRequest $request, $farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $feedingColonyId, $request->validated());

            return redirect()
                ->route('admin.care-livestock.feeding-colony.index', $farmId)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->delete($farm, $feedingColonyId);

            return redirect()
                ->route('admin.care-livestock.feeding-colony.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

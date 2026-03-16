<?php

namespace App\Http\Controllers\Admin\CareLivestock\MutationLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\MutationIndividuStoreRequest;
use App\Http\Requests\Farming\MutationIndividuUpdateRequest;
use App\Services\Web\Farming\MutationIndividu\MutationIndividuService;

class MutationIndividuController extends Controller
{
    protected MutationIndividuService $service;

    public function __construct(MutationIndividuService $service)
    {
        $this->service = $service;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.mutation_individu.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()->get();
        $pens = $farm->pens()->get();

        return view('admin.care_livestock.mutation_individu.create', compact('farm', 'livestocks', 'pens'));
    }

    public function store(MutationIndividuStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.mutation-individu.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $mutationIndividu = $this->service->find($farm, $id);
        $fromPen = $farm->pens()->find($mutationIndividu->from);
        $toPen = $farm->pens()->find($mutationIndividu->to);

        return view('admin.care_livestock.mutation_individu.show', compact('farm', 'mutationIndividu', 'fromPen', 'toPen'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $mutationIndividu = $this->service->find($farm, $id);

        if (!$this->service->checkIsLatest($mutationIndividu)) {
            return redirect()
                ->route('admin.care-livestock.mutation-individu.index', ['farm_id' => $farmId])
                ->with('error', 'Editing is not allowed because this is an old record.');
        }

        $livestocks = $farm->livestocks()->get();
        $pens = $farm->pens()->get();

        return view('admin.care_livestock.mutation_individu.edit', compact('farm', 'mutationIndividu', 'livestocks', 'pens'));
    }

    public function update(MutationIndividuUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.mutation-individu.show', [$farmId, $id])
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
                ->route('admin.care-livestock.mutation-individu.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

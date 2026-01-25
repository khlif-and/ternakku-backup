<?php

namespace App\Services\Qurban;

use Illuminate\Http\Request;

class SalesLivestockService
{
    protected SalesLivestockCoreService $core;

    public function __construct(SalesLivestockCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $result = $this->core->list($farm, $request->all());

        return view('admin.care_livestock.sales_livestock.index', [
            'farm' => $farm,
            'sales' => $result['sales'],
            'livestocks' => $result['livestocks']
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $livestocks = $farm->livestocks()->alive()->get();

        return view('admin.care_livestock.sales_livestock.create', compact('farm', 'livestocks'));
    }

    public function store($request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->core->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.sales-livestock.index', $farmId)
                ->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data');
        }
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $salesLivestock = $this->core->find($farm, $id);

        return view('admin.care_livestock.sales_livestock.show', compact('farm', 'salesLivestock'));
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $salesLivestock = $this->core->find($farm, $id);
        $livestocks = $farm->livestocks()->alive()->get();

        return view('admin.care_livestock.sales_livestock.edit', compact('farm', 'salesLivestock', 'livestocks'));
    }

    public function update($request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->core->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.sales-livestock.index', $farmId)
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data');
        }
    }

    public function destroy($farmId, $id, Request $request)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->core->delete($farm, $id);

            return redirect()
                ->route('admin.care-livestock.sales-livestock.index', $farmId)
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data');
        }
    }
}
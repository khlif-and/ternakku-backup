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

        return view('admin.care_livestock.sales_livestock.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.sales_livestock.create', compact('farm'));
    }

    public function store($request, $farmId)
    {
        return $this->core->store(request()->attributes->get('farm'), $request->validated());
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $salesLivestock = $this->core->find($farm, $id);

        return view('admin.care_livestock.sales_livestock.show', compact('farm', 'salesLivestock'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $salesLivestock = $this->core->find($farm, $id);

        return view('admin.care_livestock.sales_livestock.edit', compact('farm', 'salesLivestock'));
    }

    public function update($request, $farmId, $id)
    {
        return $this->core->update(request()->attributes->get('farm'), $id, $request->validated());
    }

    public function destroy($farmId, $id)
    {
        return $this->core->delete(request()->attributes->get('farm'), $id);
    }
}
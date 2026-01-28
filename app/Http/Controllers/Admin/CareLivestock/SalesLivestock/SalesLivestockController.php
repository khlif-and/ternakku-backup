<?php

namespace App\Http\Controllers\Admin\CareLivestock\SalesLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Qurban\SalesLivestockStoreRequest;
use App\Http\Requests\Qurban\SalesLivestockUpdateRequest;
use App\Services\Web\Qurban\SalesLivestock\SalesLivestockCoreService;

class SalesLivestockController extends Controller
{
    protected SalesLivestockCoreService $service;

    public function __construct(SalesLivestockCoreService $service)
    {
        $this->service = $service;
    }

    public function index($farmId)
    {
        $farm = \App\Models\Farm::findOrFail($farmId);
        return view('admin.care_livestock.sales_livestock.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = \App\Models\Farm::findOrFail($farmId);
        return view('admin.care_livestock.sales_livestock.create', compact('farm'));
    }

    public function store(SalesLivestockStoreRequest $request, $farmId)
    {
         // Logic handled by Livewire check
         return redirect()->route('admin.care-livestock.sales-livestock.index', $farmId);
    }

    public function show($farmId, $id)
    {
        $farm = \App\Models\Farm::findOrFail($farmId);
        return view('admin.care_livestock.sales_livestock.show', compact('farm', 'id'));
    }

    public function edit($farmId, $id)
    {
        $farm = \App\Models\Farm::findOrFail($farmId);
        return view('admin.care_livestock.sales_livestock.edit', compact('farm', 'id'));
    }

    public function update(SalesLivestockUpdateRequest $request, $farmId, $id)
    {
         // Logic handled by Livewire
         return redirect()->route('admin.care-livestock.sales-livestock.index', $farmId);
    }

    public function destroy($farmId, $id)
    {
         // Logic handled by Livewire
         return redirect()->route('admin.care-livestock.sales-livestock.index', $farmId);
    }
}
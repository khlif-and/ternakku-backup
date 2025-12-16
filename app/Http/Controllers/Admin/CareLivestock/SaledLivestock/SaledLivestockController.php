<?php

namespace App\Http\Controllers\Admin\CareLivestock\SaledLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Qurban\SalesLivestockService;
use App\Http\Requests\Qurban\SalesLivestockStoreRequest;
use App\Http\Requests\Qurban\SalesLivestockUpdateRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Farm;

class SaledLivestockController extends Controller
{
    private $salesLivestockService;

    public function __construct(SalesLivestockService $salesLivestockService)
    {
        $this->salesLivestockService = $salesLivestockService;
    }

    /**
     * LIST DATA
     */
    public function index($farmId, Request $request)
    {
        $farm = Farm::findOrFail($farmId);
        $salesLivestocks = $this->salesLivestockService->getSalesLivestocks($farmId, $request);

        return view('admin.care_livestock.sales_livestock.index', [
            'farm'            => $farm,
            'farmId'          => $farmId,
            'salesLivestocks' => $salesLivestocks
        ]);
    }

    /**
     * FORM CREATE
     */
    public function create($farmId)
    {
        try {
            $farm = Farm::findOrFail($farmId);
            $availableLivestock = $this->salesLivestockService->getAvailableLivestock($farmId);

            return view('admin.care_livestock.sales_livestock.create', [
                'farm'              => $farm,
                'farmId'            => $farmId,
                'availableLivestock'=> $availableLivestock
            ]);

        } catch (\Throwable $th) {
            Log::error('Failed to load create sales livestock: ' . $th->getMessage());
            return back()->with('error', 'Gagal membuka form tambah.');
        }
    }

    /**
     * STORE
     */
    public function store(SalesLivestockStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $response = $this->salesLivestockService->storeSalesLivestock($farmId, $validated);

        if ($response['error']) {
            return back()->with('error', 'Gagal menambahkan data')->withInput();
        }

        return redirect()
            ->route('admin.care-livestock.sales-livestock.index', $farmId)
            ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * SHOW
     */
    public function show($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        $data = $this->salesLivestockService->getSalesLivestock($farmId, $id);

        return view('admin.care_livestock.sales_livestock.show', [
            'farm'  => $farm,
            'farmId'=> $farmId,
            'item'  => $data
        ]);
    }

    /**
     * EDIT
     */
    public function edit($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        $data = $this->salesLivestockService->getSalesLivestock($farmId, $id);
        $available = $this->salesLivestockService->getAvailableLivestock($farmId);

        return view('admin.care_livestock.sales_livestock.edit', [
            'farm'      => $farm,
            'farmId'    => $farmId,
            'item'      => $data,
            'livestocks'=> $available
        ]);
    }

    /**
     * UPDATE
     */
    public function update(SalesLivestockUpdateRequest $request, $farmId, $id)
    {
        $validated = $request->validated();
        $response = $this->salesLivestockService->updateSalesLivestock($farmId, $id, $validated);

        if ($response['error']) {
            return back()->with('error', 'Gagal mengupdate data')->withInput();
        }

        return redirect()
            ->route('admin.care-livestock.sales-livestock.index', $farmId)
            ->with('success', 'Data berhasil diupdate');
    }

    /**
     * DELETE
     */
    public function destroy($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId); // optional, but sidebar needs it
        $response = $this->salesLivestockService->deleteSalesLivestock($farmId, $id);

        if ($response['error']) {
            return back()->with('error', 'Gagal menghapus data');
        }

        return redirect()
            ->route('admin.care-livestock.sales-livestock.index', $farmId)
            ->with('success', 'Data berhasil dihapus');
    }
}

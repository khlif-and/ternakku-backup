<?php

namespace App\Http\Controllers\Admin\CareLivestock\SalesLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Qurban\SalesLivestockService;
use App\Models\Livestock;

class SalesLivestockController extends Controller
{
    private $salesLivestockService;

    public function __construct(SalesLivestockService $salesLivestockService)
    {
        $this->salesLivestockService = $salesLivestockService;
    }

    /**
     * LIST DATA
     */
    public function index(Request $request, $farm_id)
    {
        $salesLivestocks = $this->salesLivestockService->getSalesLivestocks($farm_id, $request);

        return view('admin.care_livestock.sales_livestock.index', [
            'salesLivestocks' => $salesLivestocks,
            'farm_id' => $farm_id,
        ]);
    }

    /**
     * FORM TAMBAH
     */
    public function create($farm_id)
    {
        $available = $this->salesLivestockService->getAvailableLivestock($farm_id);

        return view('admin.care_livestock.sales_livestock.create', [
            'availableLivestock' => $available,
            'farm_id' => $farm_id,
        ]);
    }

    /**
     * SIMPAN
     */
    public function store(Request $request, $farm_id)
    {
        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestocks,id',
            'buyer_name'   => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'notes'        => 'nullable|string',
        ]);

        $response = $this->salesLivestockService->storeSalesLivestock($farm_id, $validated);

        if ($response['error']) {
            return back()->with('error', 'Gagal membuat data');
        }

        return redirect()
            ->route('admin.care-livestock.sales-livestock.index', $farm_id)
            ->with('success', 'Data berhasil dibuat');
    }

    /**
     * FORM EDIT
     */
    public function edit($farm_id, $id)
    {
        $data = $this->salesLivestockService->getSalesLivestock($farm_id, $id);
        $available = $this->salesLivestockService->getAvailableLivestock($farm_id);

        return view('admin.care_livestock.sales_livestock.edit', [
            'data' => $data,
            'availableLivestock' => $available,
            'farm_id' => $farm_id,
        ]);
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $farm_id, $id)
    {
        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestocks,id',
            'buyer_name'   => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'notes'        => 'nullable|string',
        ]);

        $response = $this->salesLivestockService->updateSalesLivestock($farm_id, $id, $validated);

        if ($response['error']) {
            return back()->with('error', 'Gagal mengupdate data');
        }

        return redirect()
            ->route('admin.care-livestock.sales-livestock.index', $farm_id)
            ->with('success', 'Data berhasil diperbarui');
    }

    /**
     * HAPUS
     */
    public function destroy($farm_id, $id)
    {
        $response = $this->salesLivestockService->deleteSalesLivestock($farm_id, $id);

        if ($response['error']) {
            return back()->with('error', 'Gagal menghapus data');
        }

        return redirect()
            ->route('admin.care-livestock.sales-livestock.index', $farm_id)
            ->with('success', 'Data berhasil dihapus');
    }
}

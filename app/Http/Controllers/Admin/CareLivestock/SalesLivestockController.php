<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Qurban\SalesLivestockService;
use Illuminate\Support\Facades\Log;
use App\Models\Farm;


class SalesLivestockController extends Controller
{
    private SalesLivestockService $salesLivestockService;

    public function __construct(SalesLivestockService $salesLivestockService)
    {
        $this->salesLivestockService = $salesLivestockService;
    }

public function index($farmId, Request $request)
{
    try {
        $farm = Farm::findOrFail($farmId); // INI WAJIB
        $salesLivestocks = $this->salesLivestockService->getSalesLivestocks($farmId, $request->all());

        return view('admin.care_livestock.sales_livestock.index', compact('salesLivestocks', 'farmId', 'farm'));
    } catch (\Throwable $th) {
        Log::error('Failed to load sales livestock index: ' . $th->getMessage());
        return back()->with('error', 'Gagal memuat data penjualan ternak.');
    }
}


    public function create($farmId)
    {
        try {
            $availableLivestock = $this->salesLivestockService->getAvailableLivestock($farmId);
            return view('admin.care_livestock.sales_livestock.create', compact('availableLivestock', 'farmId'));
        } catch (\Throwable $th) {
            Log::error('Failed to load create sales livestock: ' . $th->getMessage());
            return back()->with('error', 'Gagal membuka form tambah.');
        }
    }

    public function store(Request $request, $farmId)
    {
        try {
            $validated = $request->validate([
                'livestock_id' => 'required|exists:livestocks,id',
                'price' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);

            $result = $this->salesLivestockService->storeSalesLivestock($farmId, $validated);

            if ($result['error']) {
                return back()->with('error', 'Gagal menyimpan data.');
            }

            return redirect()->route('admin.care-livestock.sales-livestock.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $th) {
            Log::error('Store sales livestock error: ' . $th->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function edit($farmId, $id)
    {
        try {
            $data = $this->salesLivestockService->getSalesLivestock($farmId, $id);
            return view('admin.care_livestock.sales_livestock.edit', compact('data', 'farmId'));
        } catch (\Throwable $th) {
            Log::error('Edit sales livestock error: ' . $th->getMessage());
            return back()->with('error', 'Gagal memuat data untuk diedit.');
        }
    }

    public function update(Request $request, $farmId, $id)
    {
        try {
            $validated = $request->validate([
                'price' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);

            $result = $this->salesLivestockService->updateSalesLivestock($farmId, $id, $validated);

            if ($result['error']) {
                return back()->with('error', 'Gagal mengupdate data.');
            }

            return redirect()->route('admin.care-livestock.sales-livestock.index', $farmId)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $th) {
            Log::error('Update sales livestock error: ' . $th->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    public function destroy($farmId, $id)
    {
        try {
            $result = $this->salesLivestockService->deleteSalesLivestock($farmId, $id);

            if ($result['error']) {
                return back()->with('error', 'Gagal menghapus data.');
            }

            return back()->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error('Delete sales livestock error: ' . $th->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

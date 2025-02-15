<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockResource;
use App\Services\Qurban\SalesLivestockService;
use App\Http\Resources\Qurban\SalesLivestockResource;
use App\Http\Requests\Qurban\SalesLivestockStoreRequest;
use App\Http\Requests\Qurban\SalesLivestockUpdateRequest;

class SalesLivestockController extends Controller
{
    private $salesLivestockService;

    public function __construct(SalesLivestockService $salesLivestockService)
    {
        $this->salesLivestockService = $salesLivestockService;
    }

    public function availableLivestock(Request $request, $farm_id)
    {
        $livestockAvailable = $this->salesLivestockService->getAvailableLivestock($farm_id);

        // Dapatkan hasil akhir dan koleksi sebagai resource
        $data = LivestockResource::collection($livestockAvailable);

        return ResponseHelper::success($data, 'Livestocks retrieved successfully');
    }

    public function store(SalesLivestockStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        $response =  $this->salesLivestockService->storeSalesLivestock($farm_id, $validated);

        if($response['error']){
            return ResponseHelper::error('Failed to create data', 500);
        }

        return ResponseHelper::success(new SalesLivestockResource($response['data']), 'Data created successfully', 200);
    }

    // public function show($farmId, $id)
    // {
    //     $salesLivestock = QurbanSaleLivestock::findOrFail($id);

    //     return ResponseHelper::success(new SalesLivestockResource($salesLivestock), 'SalesLivestock found', 200);
    // }

    // public function index($farmId)
    // {
    //     $salesLivestocks = QurbanSaleLivestock::where('farm_id' , $farmId)->get();

    //     return ResponseHelper::success(SalesLivestockResource::collection($salesLivestocks), 'SalesLivestocks found', 200);
    // }

    // public function update(SalesLivestockUpdateRequest $request, $farm_id, $id)
    // {
    //     $validated = $request->validated();

    //     DB::beginTransaction();

    //     try {
    //         $salesLivestock = QurbanSaleLivestock::findOrFail($id);

    //         // Simpan data ke tabel SalesLivestocks
    //         $salesLivestock->update([
    //             'qurban_customer_id'          => $validated['customer_id'],
    //             'order_date'           => $validated['order_date'],
    //             'quantity'           => $validated['quantity'],
    //             'total_weight'           => $validated['total_weight'],
    //             'description'           => $validated['description'],
    //         ]);

    //         // Commit transaksi
    //         DB::commit();

    //         return ResponseHelper::success(new SalesLivestockResource($salesLivestock), 'SalesLivestock updated successfully', 200);
    //     } catch (\Exception $e) {
    //         // Rollback transaksi jika terjadi kesalahan
    //         DB::rollBack();

    //         return ResponseHelper::error('Failed to update SalesLivestock: ' . $e->getMessage(), 500);
    //     }
    // }

    // public function destroy($farm_id, $id)
    // {
    //     $response = $this->salesLivestockService->deleteSalesLivestock($farm_id, $id);

    //     if($response['error']) {
    //         return ResponseHelper::error('Failed to delete Sales Order', 500);
    //     }

    //     return ResponseHelper::success(null, 'Sales Order deleted successfully', 200);
    // }
}

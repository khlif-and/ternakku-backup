<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\QurbanSalesOrder;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Qurban\SalesOrderResource;
use App\Http\Requests\Qurban\SalesOrderStoreRequest;
use App\Http\Requests\Qurban\SalesOrderUpdateRequest;

class SalesOrderController extends Controller
{

    public function availableLivestock(Request $request)
    {
        $farm = $request->attributes->get('farm');

        // Ambil semua livestock yang statusnya 'HIDUP'
        $livestocks = $farm->livestocks()->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)->get();

        // Ambil ID dari sales orders yang sudah ada
        $salesOrderIds = QurbanSalesOrder::whereIn('livestock_id', $livestocks->pluck('id'))->pluck('livestock_id');

        // Filter livestock yang tidak ada dalam sales orders
        $livestockAvailable = $livestocks->whereNotIn('id', $salesOrderIds);

        // Dapatkan hasil akhir dan koleksi sebagai resource
        $data = LivestockResource::collection($livestockAvailable);

        return ResponseHelper::success($data, 'Livestocks retrieved successfully');

    }

    public function store(SalesOrderStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Simpan data ke tabel SalesOrders
            $salesOrder = QurbanSalesOrder::create([
                'farm_id'          => $farm_id,
                'qurban_customer_id'          => $validated['customer_id'],
                'order_date'           => $validated['order_date'],
                'quantity'           => $validated['quantity'],
                'total_weight'           => $validated['total_weight'],
                'description'           => $validated['description'],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new SalesOrderResource($salesOrder), 'SalesOrder created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create SalesOrder: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $salesOrder = QurbanSalesOrder::findOrFail($id);

        return ResponseHelper::success(new SalesOrderResource($salesOrder), 'SalesOrder found', 200);
    }

    public function index($farmId)
    {
        $salesOrders = QurbanSalesOrder::where('farm_id' , $farmId)->get();

        return ResponseHelper::success(SalesOrderResource::collection($salesOrders), 'SalesOrders found', 200);
    }

    public function update(SalesOrderUpdateRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $salesOrder = QurbanSalesOrder::findOrFail($id);

            // Simpan data ke tabel SalesOrders
            $salesOrder->update([
                'qurban_customer_id'          => $validated['customer_id'],
                'order_date'           => $validated['order_date'],
                'quantity'           => $validated['quantity'],
                'total_weight'           => $validated['total_weight'],
                'description'           => $validated['description'],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new SalesOrderResource($salesOrder), 'SalesOrder updated successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update SalesOrder: ' . $e->getMessage(), 500);
        }
    }
}

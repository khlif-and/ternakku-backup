<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\QurbanSalesOrder;
use App\Models\QurbanSalesOrderD;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockResource;
use App\Services\Qurban\SalesOrderService;
use App\Http\Resources\Qurban\SalesOrderResource;
use App\Http\Requests\Qurban\SalesOrderStoreRequest;
use App\Http\Requests\Qurban\SalesOrderUpdateRequest;

class SalesOrderController extends Controller
{
    private $salesOrderService;

    public function __construct(SalesOrderService $salesOrderService)
    {
        $this->salesOrderService = $salesOrderService;
    }

    public function store(SalesOrderStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Simpan data ke tabel QurbanSalesOrders
            $salesOrder = QurbanSalesOrder::create([
                'farm_id' => $farm_id,
                'qurban_customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'created_by' => auth()->user()->id,
            ]);

            // Loop detail dan simpan ke tabel detail
            foreach ($validated['details'] as $item) {
                QurbanSalesOrderD::create([
                    'qurban_sales_order_id' => $salesOrder->id,
                    'livestock_type_id'     => $item['livestock_type_id'],
                    'total_weight'          => $item['total_weight'],
                    'quantity'              => $item['quantity'],
                ]);
            }


            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new SalesOrderResource($salesOrder), 'SalesOrder created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create SalesOrder: ' . $e->getMessage(), 500);
        }
    }

    public function show($farmId, $id)
    {
        $salesOrder = QurbanSalesOrder::findOrFail($id);

        return ResponseHelper::success(new SalesOrderResource($salesOrder), 'SalesOrder found', 200);
    }

    public function index($farmId, Request $request)
    {
        $salesOrders = QurbanSalesOrder::where('farm_id', $farmId)
        ->when($request->qurban_customer_id, function ($query) use ($request) {
            $query->where('qurban_customer_id', $request->qurban_customer_id);
        })
        ->filerMarketing($farmId)
        ->get();

        return ResponseHelper::success(SalesOrderResource::collection($salesOrders), 'SalesOrders found', 200);

    }

    public function update(SalesOrderUpdateRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $salesOrder = QurbanSalesOrder::findOrFail($id);

            // Update data SalesOrder utama
            $salesOrder->update([
                'qurban_customer_id' => $validated['customer_id'],
                'order_date'         => $validated['order_date'],
            ]);

            // Hapus semua detail lama
            $salesOrder->qurbanSalesOrderD()->delete();

            // Loop detail baru dari request
            foreach ($validated['details'] as $item) {
                QurbanSalesOrderD::create([
                    'qurban_sales_order_id' => $salesOrder->id,
                    'livestock_type_id'     => $item['livestock_type_id'],
                    'total_weight'          => $item['total_weight'],
                    'quantity'              => $item['quantity'],
                ]);
            }


            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new SalesOrderResource($salesOrder), 'SalesOrder updated successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update SalesOrder: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($farm_id, $id)
    {
        $response = $this->salesOrderService->deleteSalesOrder($farm_id, $id);

        if($response['error']) {
            return ResponseHelper::error('Failed to delete Sales Order', 500);
        }

        return ResponseHelper::success(null, 'Sales Order deleted successfully', 200);
    }
}

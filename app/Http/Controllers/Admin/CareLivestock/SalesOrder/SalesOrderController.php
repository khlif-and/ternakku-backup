<?php

namespace App\Http\Controllers\Admin\CareLivestock\SalesOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\QurbanSalesOrder;
use App\Models\QurbanSalesOrderD;
use App\Services\Qurban\SalesOrderService;

class SalesOrderController extends Controller
{
    private $salesOrderService;

    public function __construct(SalesOrderService $salesOrderService)
    {
        $this->salesOrderService = $salesOrderService;
    }

    /**
     * LIST DATA (paginate fix)
     */
    public function index(Request $request, $farm_id)
    {
        try {
            $salesOrders = QurbanSalesOrder::where('farm_id', $farm_id)
                ->when($request->qurban_customer_id, function ($query) use ($request) {
                    $query->where('qurban_customer_id', $request->qurban_customer_id);
                })
                ->filterMarketing($farm_id)
                ->orderBy('id', 'desc')
                ->paginate(15); // ← FIX: wajib paginate

            return view('admin.care_livestock.sales_order.index', [
                'salesOrders' => $salesOrders,
                'farm_id'     => $farm_id,
            ]);

        } catch (\Throwable $e) {

            Log::error('SalesOrder Index Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal memuat Sales Order.');
        }
    }

    /**
     * FORM TAMBAH
     */
    public function create($farm_id)
    {
        try {
            // Data pendukung
            $customers       = \App\Models\QurbanCustomer::all();
            $livestockTypes  = \App\Models\LivestockType::all();

            return view('admin.care_livestock.sales_order.create', [
                'farm_id'        => $farm_id,
                'customers'      => $customers,
                'livestockTypes' => $livestockTypes,
            ]);

        } catch (\Throwable $e) {

            Log::error('SalesOrder Create Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal membuka halaman tambah.');
        }
    }

    /**
     * SIMPAN SALES ORDER
     */
    public function store(Request $request, $farm_id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'order_date'  => 'required|date',
            'details'     => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $salesOrder = QurbanSalesOrder::create([
                'farm_id'             => $farm_id,
                'qurban_customer_id'  => $validated['customer_id'],
                'order_date'          => $validated['order_date'],
                'created_by'          => auth()->user()->id,
            ]);

            foreach ($validated['details'] as $item) {
                QurbanSalesOrderD::create([
                    'qurban_sales_order_id' => $salesOrder->id,
                    'livestock_type_id'     => $item['livestock_type_id'],
                    'total_weight'          => $item['total_weight'],
                    'quantity'              => $item['quantity'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.sales-order.index', $farm_id)
                ->with('success', 'Sales Order berhasil dibuat.');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('SalesOrder Store Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal menyimpan Sales Order.');
        }
    }

    /**
     * FORM EDIT
     */
    public function edit($farm_id, $id)
    {
        try {
            $salesOrder = QurbanSalesOrder::with('qurbanSalesOrderD')->findOrFail($id);

            $customers      = \App\Models\QurbanCustomer::all();
            $livestockTypes = \App\Models\LivestockType::all();

            return view('admin.care_livestock.sales_order.edit', [
                'salesOrder'     => $salesOrder,
                'farm_id'        => $farm_id,
                'customers'      => $customers,
                'livestockTypes' => $livestockTypes,
            ]);

        } catch (\Throwable $e) {

            Log::error('SalesOrder Edit Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal membuka halaman edit.');
        }
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $farm_id, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'order_date'  => 'required|date',
            'details'     => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $salesOrder = QurbanSalesOrder::findOrFail($id);

            $salesOrder->update([
                'qurban_customer_id' => $validated['customer_id'],
                'order_date'         => $validated['order_date'],
            ]);

            // Hapus detail lama
            $salesOrder->qurbanSalesOrderD()->delete();

            // Insert detail baru
            foreach ($validated['details'] as $item) {
                QurbanSalesOrderD::create([
                    'qurban_sales_order_id' => $salesOrder->id,
                    'livestock_type_id'     => $item['livestock_type_id'],
                    'total_weight'          => $item['total_weight'],
                    'quantity'              => $item['quantity'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.sales-order.index', $farm_id)
                ->with('success', 'Sales Order berhasil diperbarui.');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('SalesOrder Update Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal memperbarui Sales Order.');
        }
    }

    /**
     * DELETE
     */
    public function destroy($farm_id, $id)
    {
        try {
            $response = $this->salesOrderService->deleteSalesOrder($farm_id, $id);

            if ($response['error']) {
                return back()->with('error', 'Gagal menghapus Sales Order.');
            }

            return back()->with('success', 'Sales Order berhasil dihapus.');

        } catch (\Throwable $e) {

            Log::error('SalesOrder Delete Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal menghapus Sales Order.');
        }
    }
}

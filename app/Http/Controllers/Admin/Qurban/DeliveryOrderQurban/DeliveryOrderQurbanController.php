<?php

namespace App\Http\Controllers\Admin\Qurban\DeliveryOrderQurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Qurban\DeliveryOrderQurban\QurbanDeliveryOrderService;

class DeliveryOrderQurbanController extends Controller
{
    protected QurbanDeliveryOrderService $service;

    public function __construct(QurbanDeliveryOrderService $service)
    {
        $this->service = $service;
    }

    private function getFarm()
    {
        $farm = request()->attributes->get('farm');

        if (!$farm && session()->has('selected_farm')) {
            $farm = \App\Models\Farm::find(session('selected_farm'));
        }

        return $farm;
    }

    public function index(Request $request)
    {
        $farm = $this->getFarm();

        return view('admin.qurban.qurbanDeliveryOrderData.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();

        return view('admin.qurban.qurbanDeliveryOrderData.create', compact('farm'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'qurban_sales_livestock_id' => 'required|integer|exists:qurban_sale_livestock_h,id',
            'transaction_date' => 'required|date',
        ]);

        try {
            $farm = $this->getFarm();
            $validated['farm_id'] = $farm->id;
            $response = $this->service->store($validated);

            if ($response['error']) {
                return redirect()->route('admin.qurban.delivery_order_qurban.create')
                    ->with('error', 'Gagal membuat data pengiriman.');
            }

            $firstOrder = $response['data'][0] ?? null;

            if ($firstOrder) {
                return redirect()->route('admin.qurban.delivery_order_qurban.show', $firstOrder->id)
                    ->with('success', 'Data pengiriman berhasil dibuat.');
            }

            return redirect()->route('admin.qurban.delivery_order_qurban.index')
                ->with('success', 'Data pengiriman berhasil dibuat.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.qurban.delivery_order_qurban.create')
                ->with('error', 'Gagal membuat data pengiriman.');
        }
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->service->find($id);

        return view('admin.qurban.qurbanDeliveryOrderData.show', compact('farm', 'delivery'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->service->find($id);

        return view('admin.qurban.qurbanDeliveryOrderData.edit', compact('farm', 'delivery'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'delivery_schedule' => 'required|date',
        ]);

        try {
            $farm = $this->getFarm();
            $response = $this->service->updateSchedule($farm->id, $id, $validated['delivery_schedule']);

            if ($response['error']) {
                return redirect()->route('admin.qurban.delivery_order_qurban.edit', $id)
                    ->with('error', 'Gagal memperbarui jadwal pengiriman.');
            }

            return redirect()->route('admin.qurban.delivery_order_qurban.show', $id)
                ->with('success', 'Jadwal pengiriman berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.qurban.delivery_order_qurban.edit', $id)
                ->with('error', 'Gagal memperbarui jadwal pengiriman.');
        }
    }

    public function destroy($id)
    {
        try {
            $farm = $this->getFarm();
            $response = $this->service->delete($farm->id, $id);

            if ($response['error']) {
                return redirect()->route('admin.qurban.delivery_order_qurban.index')
                    ->with('error', 'Gagal menghapus data pengiriman.');
            }

            return redirect()->route('admin.qurban.delivery_order_qurban.index')
                ->with('success', 'Data pengiriman berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.qurban.delivery_order_qurban.index')
                ->with('error', 'Gagal menghapus data pengiriman.');
        }
    }
}
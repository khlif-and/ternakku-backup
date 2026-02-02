<?php

namespace App\Services\Web\Qurban\DeliveryOrderQurban;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Services\Web\Qurban\DeliveryOrderQurban\QurbanDeliveryOrderCoreService;

class QurbanDeliveryOrderService
{
    protected QurbanDeliveryOrderCoreService $core;

    public function __construct(QurbanDeliveryOrderCoreService $core)
    {
        $this->core = $core;
    }

    private function getFarm()
    {
        $farm = request()->attributes->get('farm');

        if (!$farm && session()->has('selected_farm')) {
            $farm = Farm::find(session('selected_farm'));
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

    public function store(array $data)
    {
        $farm = $this->getFarm();
        $data['farm_id'] = $farm->id;

        $response = $this->core->store($data);

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
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.qurbanDeliveryOrderData.show', compact('farm', 'delivery'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.qurbanDeliveryOrderData.edit', compact('farm', 'delivery'));
    }

    public function updateSchedule($id, $schedule)
    {
        $farm = $this->getFarm();
        $response = $this->core->updateSchedule($farm->id, $id, $schedule);

        if ($response['error']) {
            return redirect()->route('admin.qurban.delivery_order_qurban.edit', $id)
                ->with('error', 'Gagal memperbarui jadwal pengiriman.');
        }

        return redirect()->route('admin.qurban.delivery_order_qurban.show', $id)
            ->with('success', 'Jadwal pengiriman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $farm = $this->getFarm();
        $response = $this->core->delete($farm->id, $id);

        if ($response['error']) {
            return redirect()->route('admin.qurban.delivery_order_qurban.index')
                ->with('error', 'Gagal menghapus data pengiriman.');
        }

        return redirect()->route('admin.qurban.delivery_order_qurban.index')
            ->with('success', 'Data pengiriman berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Admin\Qurban\QurbanDelivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryService;

class QurbanDeliveryController extends Controller
{
    protected QurbanDeliveryService $service;

    public function __construct(QurbanDeliveryService $service)
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

        return view('admin.qurban.qurban_delivery.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();

        return view('admin.qurban.qurban_delivery.create', compact('farm'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_date' => 'required|date',
            'driver_id' => 'required|exists:users,id',
            'fleet_id' => 'required|exists:qurban_fleets,id',
            'delivery_order_ids' => 'required|array|min:1',
            'delivery_order_ids.*' => 'exists:qurban_delivery_order_h,id',
        ]);

        try {
            $farm = $this->getFarm();
            $response = $this->service->store($farm->id, $validated);

            if ($response['error']) {
                return redirect()->route('admin.qurban.qurban_delivery.create')
                    ->with('error', 'Gagal membuat instruksi pengiriman.');
            }

            return redirect()->route('admin.qurban.qurban_delivery.show', $response['data']->id)
                ->with('success', 'Instruksi pengiriman berhasil dibuat.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.qurban.qurban_delivery.create')
                ->with('error', 'Gagal membuat instruksi pengiriman.');
        }
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->service->find($id);

        return view('admin.qurban.qurban_delivery.show', compact('farm', 'delivery'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->service->find($id);

        return view('admin.qurban.qurban_delivery.edit', compact('farm', 'delivery'));
    }

    public function update(Request $request, $id)
    {
        try {
            $farm = $this->getFarm();
            $this->service->setReadyToDeliver($farm->id, $id);

            return redirect()->route('admin.qurban.qurban_delivery.show', $id)
                ->with('success', 'Status berhasil diubah ke Ready to Deliver.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.qurban.qurban_delivery.show', $id)
                ->with('error', 'Gagal mengubah status pengiriman.');
        }
    }

    public function destroy($id)
    {
        try {
            $farm = $this->getFarm();
            $response = $this->service->delete($farm->id, $id);

            if ($response['error']) {
                return redirect()->route('admin.qurban.qurban_delivery.index')
                    ->with('error', 'Gagal menghapus instruksi pengiriman.');
            }

            return redirect()->route('admin.qurban.qurban_delivery.index')
                ->with('success', 'Instruksi pengiriman berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.qurban.qurban_delivery.index')
                ->with('error', 'Gagal menghapus instruksi pengiriman.');
        }
    }
}
<?php

namespace App\Services\Web\Qurban\QurbanDelivery;

use Illuminate\Http\Request;
use App\Models\Farm;

class QurbanDeliveryService
{
    protected QurbanDeliveryCoreService $core;

    public function __construct(QurbanDeliveryCoreService $core)
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

        return view('admin.qurban.qurban_delivery.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();

        return view('admin.qurban.qurban_delivery.create', compact('farm'));
    }

    public function store(array $data)
    {
        $farm = $this->getFarm();

        $response = $this->core->store($farm->id, $data);

        if ($response['error']) {
            return redirect()->route('admin.qurban.qurban_delivery.create')
                ->with('error', 'Gagal membuat instruksi pengiriman.');
        }

        return redirect()->route('admin.qurban.qurban_delivery.show', $response['data']->id)
            ->with('success', 'Instruksi pengiriman berhasil dibuat.');
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.qurban_delivery.show', compact('farm', 'delivery'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.qurban_delivery.edit', compact('farm', 'delivery'));
    }

    public function setReadyToDeliver($id)
    {
        $farm = $this->getFarm();

        try {
            $this->core->setReadyToDeliver($farm->id, $id);
            return redirect()->route('admin.qurban.qurban_delivery.show', $id)
                ->with('success', 'Status berhasil diubah ke Ready to Deliver.');
        } catch (\Exception $e) {
            return redirect()->route('admin.qurban.qurban_delivery.show', $id)
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $farm = $this->getFarm();

        $response = $this->core->delete($farm->id, $id);

        if ($response['error']) {
            return redirect()->route('admin.qurban.qurban_delivery.index')
                ->with('error', 'Gagal menghapus instruksi pengiriman.');
        }

        return redirect()->route('admin.qurban.qurban_delivery.index')
            ->with('success', 'Instruksi pengiriman berhasil dihapus.');
    }
}
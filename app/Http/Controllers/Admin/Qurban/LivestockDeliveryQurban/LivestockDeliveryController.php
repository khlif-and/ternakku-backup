<?php

namespace App\Http\Controllers\Admin\Qurban\LivestockDeliveryQurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteService;

class LivestockDeliveryController extends Controller
{
    protected LivestockDeliveryNoteService $service;

    public function __construct(LivestockDeliveryNoteService $service)
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

        return view('admin.qurban.livestock_delivery_note_qurban.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();

        return view('admin.qurban.livestock_delivery_note_qurban.create', compact('farm'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'qurban_sales_livestock_id' => 'required|integer|exists:qurban_sale_livestock_h,id',
            'transaction_date' => 'required|date',
        ]);

        try {
            $farm = $this->getFarm();
            $response = $this->service->store($farm->id, $validated);

            if ($response['error']) {
                return redirect()->route('qurban.livestock-delivery-note.create')
                    ->with('error', 'Gagal membuat surat jalan.');
            }

            $firstOrder = $response['data'][0] ?? null;

            if ($firstOrder) {
                return redirect()->route('qurban.livestock-delivery-note.show', $firstOrder->id)
                    ->with('success', 'Surat jalan berhasil dibuat.');
            }

            return redirect()->route('qurban.livestock-delivery-note.index')
                ->with('success', 'Surat jalan berhasil dibuat.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('qurban.livestock-delivery-note.create')
                ->with('error', 'Gagal membuat surat jalan.');
        }
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->service->find($id);

        return view('admin.qurban.livestock_delivery_note_qurban.show', compact('farm', 'delivery'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->service->find($id);

        return view('admin.qurban.livestock_delivery_note_qurban.edit', compact('farm', 'delivery'));
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
                return redirect()->route('qurban.livestock-delivery-note.edit', $id)
                    ->with('error', 'Gagal memperbarui jadwal pengiriman.');
            }

            return redirect()->route('qurban.livestock-delivery-note.show', $id)
                ->with('success', 'Jadwal pengiriman berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('qurban.livestock-delivery-note.edit', $id)
                ->with('error', 'Gagal memperbarui jadwal pengiriman.');
        }
    }

    public function destroy($id)
    {
        try {
            $farm = $this->getFarm();
            $response = $this->service->delete($farm->id, $id);

            if ($response['error']) {
                return redirect()->route('qurban.livestock-delivery-note.index')
                    ->with('error', 'Gagal menghapus surat jalan.');
            }

            return redirect()->route('qurban.livestock-delivery-note.index')
                ->with('success', 'Surat jalan berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('qurban.livestock-delivery-note.index')
                ->with('error', 'Gagal menghapus surat jalan.');
        }
    }
}
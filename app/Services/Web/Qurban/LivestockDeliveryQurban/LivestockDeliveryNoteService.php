<?php

namespace App\Services\Web\Qurban\LivestockDeliveryQurban;

use Illuminate\Http\Request;
use App\Models\Farm;

class LivestockDeliveryNoteService
{
    protected LivestockDeliveryNoteCoreService $core;

    public function __construct(LivestockDeliveryNoteCoreService $core)
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

        return view('admin.qurban.livestock_delivery_note_qurban.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();

        return view('admin.qurban.livestock_delivery_note_qurban.create', compact('farm'));
    }

    public function store(array $data)
    {
        $farm = $this->getFarm();

        $response = $this->core->store($farm->id, $data);

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
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.livestock_delivery_note_qurban.show', compact('farm', 'delivery'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.livestock_delivery_note_qurban.edit', compact('farm', 'delivery'));
    }

    public function updateSchedule($id, $schedule)
    {
        $farm = $this->getFarm();
        $response = $this->core->updateSchedule($farm->id, $id, $schedule);

        if ($response['error']) {
            return redirect()->route('qurban.livestock-delivery-note.edit', $id)
                ->with('error', 'Gagal memperbarui jadwal pengiriman.');
        }

        return redirect()->route('qurban.livestock-delivery-note.show', $id)
            ->with('success', 'Jadwal pengiriman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $farm = $this->getFarm();
        $response = $this->core->delete($farm->id, $id);

        if ($response['error']) {
            return redirect()->route('qurban.livestock-delivery-note.index')
                ->with('error', 'Gagal menghapus surat jalan.');
        }

        return redirect()->route('qurban.livestock-delivery-note.index')
            ->with('success', 'Surat jalan berhasil dihapus.');
    }
}
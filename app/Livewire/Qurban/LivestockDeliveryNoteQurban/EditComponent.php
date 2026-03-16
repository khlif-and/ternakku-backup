<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanDeliveryOrderH $deliveryNote;

    public $delivery_schedule;

    protected function rules()
    {
        return [
            'delivery_schedule' => 'required|date',
        ];
    }

    protected $messages = [
        'delivery_schedule.required' => 'Jadwal pengiriman wajib diisi.',
    ];

    public function mount(Farm $farm, QurbanDeliveryOrderH $deliveryNote)
    {
        $this->farm = $farm;
        $this->deliveryNote = $deliveryNote->load([
            'qurbanSaleLivestockH.qurbanCustomer.user',
            'qurbanCustomerAddress.qurbanCustomer.user'
        ]);

        $this->delivery_schedule = $deliveryNote->delivery_schedule;
    }

    public function save(LivestockDeliveryNoteCoreService $coreService)
    {
        $this->validate();

        try {
            $response = $coreService->updateSchedule($this->farm->id, $this->deliveryNote->id, $this->delivery_schedule);

            if ($response['error']) {
                session()->flash('error', 'Gagal memperbarui jadwal pengiriman.');
                return;
            }

            session()->flash('success', 'Jadwal pengiriman berhasil diperbarui.');
            return redirect()->route('qurban.livestock-delivery-note.show', $this->deliveryNote->id);

        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-delivery-note-qurban.edit-component');
    }
}

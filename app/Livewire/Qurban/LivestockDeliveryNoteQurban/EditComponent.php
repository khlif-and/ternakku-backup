<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanDeliveryOrderH $deliveryNote;

    public $transaction_number;
    public $customer_name;
    public $delivery_date;

    protected function rules()
    {
        return [
            'delivery_date' => 'required|date',
        ];
    }

    public function mount(Farm $farm, QurbanDeliveryOrderH $deliveryNote)
    {
        $this->farm = $farm;
        $this->deliveryNote = $deliveryNote->load(['qurbanSaleLivestockH', 'qurbanCustomerAddress.qurbanCustomer.user']);

        // Transaction number (from sale header usually, or delivery order itself doesn't have one visible in create?)
        // QurbanDeliveryOrderH has transaction_number from trait? Yes.
        // It also has qurbanSaleLivestockH relationship.

        $this->transaction_number = $deliveryNote->qurbanSaleLivestockH->transaction_number ?? '-';
        $this->customer_name = $deliveryNote->qurbanCustomerAddress->qurbanCustomer->user->name ?? '-';
        $this->delivery_date = $deliveryNote->transaction_date;
    }

    public function save(LivestockDeliveryNoteCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->deliveryNote->id, [
                'delivery_date' => $this->delivery_date,
            ]);

            return redirect()->route('qurban.livestock-delivery-note.index', $this->farm->id)
                ->with('success', 'Surat jalan berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-delivery-note-qurban.edit-component');
    }
}

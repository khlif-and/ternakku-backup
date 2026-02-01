<?php

namespace App\Livewire\Qurban\QurbanDelivery;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanDeliveryOrderH $delivery;

    public $transaction_date;

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal pengiriman wajib diisi.',
    ];

    public function mount(Farm $farm, QurbanDeliveryOrderH $delivery)
    {
        $this->farm = $farm;
        $this->delivery = $delivery->load('qurbanSaleLivestockH.qurbanCustomer.user');

        $this->transaction_date = $this->delivery->transaction_date;
    }

    public function save(QurbanDeliveryCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->delivery->id, [
                'farm_id' => $this->farm->id,
                'transaction_date' => $this->transaction_date,
            ]);

            session()->flash('success', 'Data pengiriman berhasil diperbarui.');
            return redirect()->route('admin.qurban.qurban_delivery.show', $this->delivery->id);

        } catch (\Throwable $e) {
            Log::error('Qurban Delivery Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.delivery-qurban.edit-component');
    }
}

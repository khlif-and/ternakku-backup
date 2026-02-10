<?php

namespace App\Livewire\Qurban\DeliveryOrderQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\DeliveryOrderQurban\QurbanDeliveryOrderCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanDeliveryOrderH $delivery;

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

    public function mount(Farm $farm, QurbanDeliveryOrderH $delivery)
    {
        $this->farm = $farm;
        $this->delivery = $delivery->load('qurbanSaleLivestockH.qurbanCustomer.user');

        $this->delivery_schedule = $this->delivery->delivery_schedule;
    }

    public function save(QurbanDeliveryOrderCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->updateSchedule($this->farm->id, $this->delivery->id, $this->delivery_schedule);

            session()->flash('success', 'Jadwal pengiriman berhasil diperbarui.');
            return redirect()->route('admin.qurban.delivery_order_qurban.show', $this->delivery->id);

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
        return view('livewire.qurban.delivery-order-qurban.edit-component');
    }
}
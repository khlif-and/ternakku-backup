<?php

namespace App\Livewire\Qurban\QurbanDelivery;

use Livewire\Component;
use App\Models\Farm;
use App\Models\User;
use App\Models\QurbanFleet;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $delivery_date;
    public $driver_id;
    public $fleet_id;
    public $delivery_order_ids = [];

    public $drivers = [];
    public $fleets = [];
    public $availableOrders = [];

    protected function rules()
    {
        return [
            'delivery_date' => 'required|date',
            'driver_id' => 'required|exists:users,id',
            'fleet_id' => 'required|exists:qurban_fleets,id',
            'delivery_order_ids' => 'required|array|min:1',
        ];
    }

    protected $messages = [
        'delivery_date.required' => 'Tanggal pengiriman wajib diisi.',
        'driver_id.required' => 'Driver wajib dipilih.',
        'fleet_id.required' => 'Armada wajib dipilih.',
        'delivery_order_ids.required' => 'Minimal pilih 1 surat jalan.',
        'delivery_order_ids.min' => 'Minimal pilih 1 surat jalan.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->delivery_date = now()->format('Y-m-d');

        $this->drivers = \App\Models\FarmUser::where('farm_id', $farm->id)
            ->where('farm_role', 'DRIVER')
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter();
        $this->fleets = QurbanFleet::where('farm_id', $farm->id)->get();

        $this->availableOrders = QurbanDeliveryOrderH::where('farm_id', $farm->id)
            ->whereDoesntHave('qurbanDeliveryInstructionD')
            ->with('qurbanCustomerAddress.qurbanCustomer.user')
            ->get();
    }

    public function save(QurbanDeliveryCoreService $coreService)
    {
        $this->validate();

        try {
            $response = $coreService->store($this->farm->id, [
                'delivery_date' => $this->delivery_date,
                'driver_id' => $this->driver_id,
                'fleet_id' => $this->fleet_id,
                'delivery_order_ids' => $this->delivery_order_ids,
            ]);

            if ($response['error']) {
                session()->flash('error', 'Gagal membuat instruksi pengiriman.');
                return;
            }

            session()->flash('success', 'Instruksi pengiriman berhasil dibuat.');
            return redirect()->route('admin.qurban.qurban_delivery.show', $response['data']->id);

        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.qurban.delivery-qurban.create-component');
    }
}

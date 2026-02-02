<?php

namespace App\Livewire\Qurban\QurbanDelivery;

use Livewire\Component;
use App\Models\Farm;
use App\Models\User;
use App\Models\QurbanFleet;
use App\Services\Web\Qurban\QurbanDelivery\QurbanDeliveryCoreService;

class IndexComponent extends Component
{
    public Farm $farm;
    public $start_date;
    public $end_date;
    public $status;
    public $driver_id;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'status' => ['except' => ''],
        'driver_id' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, QurbanDeliveryCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm->id, $id);
            session()->flash('success', 'Instruksi pengiriman berhasil dihapus.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(QurbanDeliveryCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'driver_id' => $this->driver_id,
        ];

        $items = $coreService->listDeliveries($this->farm->id, $filters);

        return view('livewire.qurban.delivery-qurban.index-component', [
            'items' => $items,
            'drivers' => User::whereHas('roles', fn($q) => $q->where('name', 'driver'))->get(),
            'statuses' => ['scheduled', 'ready_to_deliver', 'in_delivery', 'delivered'],
        ]);
    }
}
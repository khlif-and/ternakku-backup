<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $qurban_customer_id;
    public $status;

    protected $queryString = [
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'qurban_customer_id' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, LivestockDeliveryNoteCoreService $coreService)
    {
        try {
            $coreService->delete($this->farm->id, $id);
            session()->flash('success', 'Surat jalan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render(LivestockDeliveryNoteCoreService $coreService)
    {
        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'qurban_customer_id' => $this->qurban_customer_id,
            'status' => $this->status,
        ];

        $items = $coreService->listDeliveryNotes($this->farm->id, $filters);

        return view('livewire.qurban.livestock-delivery-note-qurban.index-component', [
            'items' => $items,
            'customers' => QurbanCustomer::where('farm_id', $this->farm->id)->get(),
            'statuses' => ['pending', 'ready_to_deliver', 'in_delivery', 'delivered'],
        ]);
    }
}

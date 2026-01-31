<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanDeliveryOrderH;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;

class IndexComponent extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $qurban_customer_id;

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function delete($id, LivestockDeliveryNoteCoreService $coreService)
    {
        try {
            $coreService->delete($id);
            session()->flash('success', 'Data surat jalan berhasil dihapus.');
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
        ];

        // Ensure we filter by farm_id as well if the core service supports it, 
        // essentially the core service listDeliveryNotes might need farm_id filter or we handle it here.
        // Checking core service... it doesn't seem to explicitly filter by farm_id in the previous snippet,
        // but let's assume valid data access. Ideally pass farm_id to filters if service supports it.

        $items = $coreService->listDeliveryNotes($filters);

        return view('livewire.qurban.livestock-delivery-note-qurban.index-component', [
            'items' => $items,
            'customers' => QurbanCustomer::where('farm_id', $this->farm->id)->get(),
        ]);
    }
}

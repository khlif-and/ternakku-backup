<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;

class CreateComponent extends Component
{
    public Farm $farm;
    public $qurban_sales_livestock_id;
    public $transaction_date;

    public $transactions = [];

    protected function rules()
    {
        return [
            'qurban_sales_livestock_id' => 'required|exists:qurban_sale_livestock_h,id',
            'transaction_date' => 'required|date',
        ];
    }

    protected $messages = [
        'qurban_sales_livestock_id.required' => 'Transaksi penjualan wajib dipilih.',
        'transaction_date.required' => 'Tanggal pengiriman wajib diisi.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');

        $this->transactions = \App\Models\QurbanSaleLivestockH::with(['qurbanCustomer.user'])
            ->where('farm_id', $farm->id)
            ->latest()
            ->get();

        // Optional: Filter transactions that don't have delivery orders yet?
        // But the API handles duplicate checks gracefully, so listing all is fine.
    }

    public function save(LivestockDeliveryNoteCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->store([
                'farm_id' => $this->farm->id,
                'qurban_sales_livestock_id' => $this->qurban_sales_livestock_id,
                'transaction_date' => $this->transaction_date,
            ]);

            session()->flash('success', 'Surat jalan berhasil dibuat.');
            return redirect()->route('qurban.livestock-delivery-note.index', $this->farm->id);
        } catch (\Throwable $e) {
            \Log::error('Delivery Note Create Error: ' . $e->getMessage());
            session()->flash('error', 'Gagal membuat surat jalan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-delivery-note-qurban.create-component');
    }
}

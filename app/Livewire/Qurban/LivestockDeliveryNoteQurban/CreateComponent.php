<?php

namespace App\Livewire\Qurban\LivestockDeliveryNoteQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanSaleLivestockH;
use App\Services\Web\Qurban\LivestockDeliveryQurban\LivestockDeliveryNoteCoreService;
use Illuminate\Support\Facades\Log;

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

        $this->transactions = QurbanSaleLivestockH::with(['qurbanCustomer.user'])
            ->where('farm_id', $farm->id)
            ->latest()
            ->get();
    }

    public function save(LivestockDeliveryNoteCoreService $coreService)
    {
        $this->validate();

        try {
            $response = $coreService->store($this->farm->id, [
                'qurban_sales_livestock_id' => $this->qurban_sales_livestock_id,
                'transaction_date' => $this->transaction_date,
            ]);

            if ($response['error']) {
                session()->flash('error', 'Gagal membuat surat jalan.');
                return;
            }

            $firstOrder = $response['data'][0] ?? null;

            session()->flash('success', 'Surat jalan berhasil dibuat.');

            if ($firstOrder) {
                return redirect()->route('qurban.livestock-delivery-note.show', $firstOrder->id);
            }

            return redirect()->route('qurban.livestock-delivery-note.index');

        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-delivery-note-qurban.create-component');
    }
}
